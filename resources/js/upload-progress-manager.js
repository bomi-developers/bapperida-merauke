/**
 * Upload Progress Manager
 * Google Drive-like background upload system with progress tracking
 */
class UploadProgressManager {
    constructor() {
        this.uploads = new Map();
        this.widget = null;
        this.isMinimized = false;
        this.uploadIdCounter = 0;
        this.init();
    }

    init() {
        this.loadFromSession();
        this.createWidget();
        this.updateWidgetVisibility();
    }

    generateId() {
        return `upload_${Date.now()}_${++this.uploadIdCounter}`;
    }

    /**
     * Start a new upload
     * @param {string} url - The endpoint URL
     * @param {FormData} formData - The form data to upload
     * @param {Object} options - Upload options
     * @returns {Promise} - Resolves with response data
     */
    upload(url, formData, options = {}) {
        const uploadId = this.generateId();
        const uploadName = options.name || "Upload";
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");

        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            const startTime = Date.now();

            // Calculate total size
            let totalSize = 0;
            for (const [key, value] of formData.entries()) {
                if (value instanceof File) {
                    totalSize += value.size;
                }
            }

            const uploadData = {
                id: uploadId,
                name: uploadName,
                status: "uploading",
                progress: 0,
                loaded: 0,
                total: totalSize || 1,
                speed: 0,
                speedFormatted: "0 KB/s",
                startTime: startTime,
                error: null,
            };

            this.uploads.set(uploadId, uploadData);
            this.saveToSession();
            this.updateWidget();
            this.showWidget();

            // Track upload progress
            xhr.upload.onprogress = (e) => {
                if (e.lengthComputable) {
                    const elapsed = (Date.now() - startTime) / 1000;
                    const speed = elapsed > 0 ? e.loaded / elapsed : 0;

                    uploadData.progress = Math.round(
                        (e.loaded / e.total) * 100
                    );
                    uploadData.loaded = e.loaded;
                    uploadData.total = e.total;
                    uploadData.speed = speed;
                    uploadData.speedFormatted = this.formatSpeed(speed);

                    this.saveToSession();
                    this.updateWidget();
                }
            };

            xhr.onload = () => {
                try {
                    const response = JSON.parse(xhr.responseText);

                    if (xhr.status >= 200 && xhr.status < 300) {
                        uploadData.status = "completed";
                        uploadData.progress = 100;
                        this.saveToSession();
                        this.updateWidget();

                        // Auto-hide completed uploads after 5 seconds
                        setTimeout(() => {
                            this.removeUpload(uploadId);
                        }, 5000);

                        if (options.onSuccess) options.onSuccess(response);
                        resolve(response);
                    } else {
                        uploadData.status = "error";
                        uploadData.error =
                            response.message || `Error ${xhr.status}`;
                        this.saveToSession();
                        this.updateWidget();

                        if (options.onError) options.onError(response);
                        reject(response);
                    }
                } catch (e) {
                    uploadData.status = "error";
                    uploadData.error = "Invalid response";
                    this.saveToSession();
                    this.updateWidget();
                    reject(e);
                }
            };

            xhr.onerror = () => {
                uploadData.status = "error";
                uploadData.error = "Network error";
                this.saveToSession();
                this.updateWidget();

                if (options.onError)
                    options.onError({ message: "Network error" });
                reject(new Error("Network error"));
            };

            xhr.open("POST", url, true);

            if (csrfToken) {
                xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);
            }
            xhr.setRequestHeader("Accept", "application/json");
            xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

            // Add method override if present
            if (formData.has("_method")) {
                // Keep _method in formData, Laravel will handle it
            }

            xhr.send(formData);
        });
    }

    formatSpeed(bytesPerSecond) {
        if (bytesPerSecond >= 1024 * 1024) {
            return (bytesPerSecond / (1024 * 1024)).toFixed(2) + " MB/s";
        } else if (bytesPerSecond >= 1024) {
            return (bytesPerSecond / 1024).toFixed(1) + " KB/s";
        }
        return Math.round(bytesPerSecond) + " B/s";
    }

    formatBytes(bytes) {
        if (bytes >= 1024 * 1024) {
            return (bytes / (1024 * 1024)).toFixed(2) + " MB";
        } else if (bytes >= 1024) {
            return (bytes / 1024).toFixed(1) + " KB";
        }
        return bytes + " B";
    }

    removeUpload(uploadId) {
        this.uploads.delete(uploadId);
        this.saveToSession();
        this.updateWidget();
        this.updateWidgetVisibility();
    }

    saveToSession() {
        const data = [];
        this.uploads.forEach((upload) => {
            data.push(upload);
        });
        sessionStorage.setItem("uploadProgress", JSON.stringify(data));
    }

    loadFromSession() {
        try {
            const data = sessionStorage.getItem("uploadProgress");
            if (data) {
                const uploads = JSON.parse(data);
                uploads.forEach((upload) => {
                    // Only restore completed or error uploads for display
                    // Don't restore in-progress uploads (they would have been interrupted)
                    if (
                        upload.status === "completed" ||
                        upload.status === "error"
                    ) {
                        this.uploads.set(upload.id, upload);
                    }
                });
            }
        } catch (e) {
            console.error("Failed to load upload progress from session", e);
        }
    }

    createWidget() {
        if (document.getElementById("upload-progress-widget")) {
            this.widget = document.getElementById("upload-progress-widget");
            return;
        }

        const widget = document.createElement("div");
        widget.id = "upload-progress-widget";
        widget.className = "upload-widget hidden";
        widget.innerHTML = `
            <div class="upload-widget-header">
                <div class="upload-widget-title">
                    <i class="bi bi-cloud-arrow-up-fill"></i>
                    <span>Upload Progress</span>
                </div>
                <div class="upload-widget-controls">
                    <button type="button" class="upload-widget-btn minimize-btn" title="Minimize">
                        <i class="bi bi-dash-lg"></i>
                    </button>
                    <button type="button" class="upload-widget-btn close-btn" title="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
            <div class="upload-widget-body">
                <div class="upload-list"></div>
            </div>
            <div class="upload-widget-minimized">
                <i class="bi bi-cloud-arrow-up-fill"></i>
                <span class="upload-count">0</span>
            </div>
        `;

        // Add styles
        if (!document.getElementById("upload-progress-styles")) {
            const styles = document.createElement("style");
            styles.id = "upload-progress-styles";
            styles.textContent = this.getStyles();
            document.head.appendChild(styles);
        }

        document.body.appendChild(widget);
        this.widget = widget;

        // Event listeners
        widget
            .querySelector(".minimize-btn")
            .addEventListener("click", () => this.toggleMinimize());
        widget
            .querySelector(".close-btn")
            .addEventListener("click", () => this.closeWidget());
        widget
            .querySelector(".upload-widget-minimized")
            .addEventListener("click", () => this.toggleMinimize());
    }

    getStyles() {
        return `
            .upload-widget {
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 360px;
                background: white;
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(0, 0, 0, 0.05);
                z-index: 9999;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
                overflow: hidden;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .dark .upload-widget {
                background: #1f2937;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.1);
            }

            .upload-widget.hidden {
                display: none;
            }

            .upload-widget.minimized .upload-widget-header,
            .upload-widget.minimized .upload-widget-body {
                display: none;
            }

            .upload-widget.minimized {
                width: 56px;
                height: 56px;
                border-radius: 50%;
                cursor: pointer;
            }

            .upload-widget.minimized .upload-widget-minimized {
                display: flex;
            }

            .upload-widget-minimized {
                display: none;
                align-items: center;
                justify-content: center;
                width: 100%;
                height: 100%;
                font-size: 22px;
                color: #4f46e5;
                position: relative;
            }

            .dark .upload-widget-minimized {
                color: #818cf8;
            }

            .upload-widget-minimized .upload-count {
                position: absolute;
                top: 8px;
                right: 8px;
                background: #ef4444;
                color: white;
                font-size: 10px;
                font-weight: 600;
                width: 18px;
                height: 18px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .upload-widget-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 14px 16px;
                background: linear-gradient(135deg, #4f46e5, #6366f1);
                color: white;
            }

            .upload-widget-title {
                display: flex;
                align-items: center;
                gap: 10px;
                font-weight: 600;
                font-size: 14px;
            }

            .upload-widget-title i {
                font-size: 18px;
            }

            .upload-widget-controls {
                display: flex;
                gap: 6px;
            }

            .upload-widget-btn {
                width: 28px;
                height: 28px;
                border: none;
                background: rgba(255, 255, 255, 0.2);
                color: white;
                border-radius: 6px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: background 0.2s;
            }

            .upload-widget-btn:hover {
                background: rgba(255, 255, 255, 0.3);
            }

            .upload-widget-body {
                max-height: 300px;
                overflow-y: auto;
            }

            .upload-list {
                padding: 8px;
            }

            .upload-item {
                padding: 12px;
                border-radius: 8px;
                background: #f8fafc;
                margin-bottom: 8px;
            }

            .dark .upload-item {
                background: #374151;
            }

            .upload-item:last-child {
                margin-bottom: 0;
            }

            .upload-item-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 8px;
            }

            .upload-item-name {
                font-size: 13px;
                font-weight: 500;
                color: #1f2937;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 200px;
            }

            .dark .upload-item-name {
                color: #f3f4f6;
            }

            .upload-item-status {
                font-size: 11px;
                font-weight: 600;
                padding: 2px 8px;
                border-radius: 9999px;
            }

            .upload-item-status.uploading {
                background: #dbeafe;
                color: #1d4ed8;
            }

            .dark .upload-item-status.uploading {
                background: #1e3a5f;
                color: #60a5fa;
            }

            .upload-item-status.completed {
                background: #dcfce7;
                color: #15803d;
            }

            .dark .upload-item-status.completed {
                background: #14532d;
                color: #86efac;
            }

            .upload-item-status.error {
                background: #fee2e2;
                color: #dc2626;
            }

            .dark .upload-item-status.error {
                background: #7f1d1d;
                color: #fca5a5;
            }

            .upload-progress-bar {
                height: 6px;
                background: #e2e8f0;
                border-radius: 3px;
                overflow: hidden;
                margin-bottom: 8px;
            }

            .dark .upload-progress-bar {
                background: #4b5563;
            }

            .upload-progress-fill {
                height: 100%;
                background: linear-gradient(90deg, #4f46e5, #6366f1);
                border-radius: 3px;
                transition: width 0.3s ease;
            }

            .upload-progress-fill.completed {
                background: linear-gradient(90deg, #22c55e, #4ade80);
            }

            .upload-progress-fill.error {
                background: linear-gradient(90deg, #ef4444, #f87171);
            }

            .upload-item-info {
                display: flex;
                justify-content: space-between;
                font-size: 11px;
                color: #64748b;
            }

            .dark .upload-item-info {
                color: #9ca3af;
            }

            .upload-item-speed {
                font-weight: 500;
                color: #4f46e5;
            }

            .dark .upload-item-speed {
                color: #818cf8;
            }

            .upload-empty {
                padding: 24px;
                text-align: center;
                color: #94a3b8;
                font-size: 13px;
            }

            .dark .upload-empty {
                color: #6b7280;
            }

            @keyframes pulse-ring {
                0% { transform: scale(0.95); opacity: 1; }
                50% { transform: scale(1.05); opacity: 0.8; }
                100% { transform: scale(0.95); opacity: 1; }
            }

            .upload-widget.minimized.has-active {
                animation: pulse-ring 2s ease-in-out infinite;
            }
        `;
    }

    updateWidget() {
        if (!this.widget) return;

        const uploadList = this.widget.querySelector(".upload-list");
        const uploadCount = this.widget.querySelector(".upload-count");

        if (this.uploads.size === 0) {
            uploadList.innerHTML =
                '<div class="upload-empty"><i class="bi bi-inbox"></i><p>No active uploads</p></div>';
            uploadCount.textContent = "0";
            return;
        }

        let html = "";
        let activeCount = 0;

        this.uploads.forEach((upload) => {
            if (upload.status === "uploading") activeCount++;

            const statusClass = upload.status;
            const progressClass =
                upload.status === "completed"
                    ? "completed"
                    : upload.status === "error"
                    ? "error"
                    : "";

            let statusLabel = "";
            switch (upload.status) {
                case "uploading":
                    statusLabel = "Uploading...";
                    break;
                case "completed":
                    statusLabel = "Completed";
                    break;
                case "error":
                    statusLabel = "Failed";
                    break;
            }

            html += `
                <div class="upload-item" data-upload-id="${upload.id}">
                    <div class="upload-item-header">
                        <span class="upload-item-name" title="${upload.name}">${
                upload.name
            }</span>
                        <span class="upload-item-status ${statusClass}">${statusLabel}</span>
                    </div>
                    <div class="upload-progress-bar">
                        <div class="upload-progress-fill ${progressClass}" style="width: ${
                upload.progress
            }%"></div>
                    </div>
                    <div class="upload-item-info">
                        <span>${this.formatBytes(
                            upload.loaded
                        )} / ${this.formatBytes(upload.total)}</span>
                        <span class="upload-item-speed">${
                            upload.status === "uploading"
                                ? upload.speedFormatted
                                : ""
                        }</span>
                        <span>${upload.progress}%</span>
                    </div>
                </div>
            `;
        });

        uploadList.innerHTML = html;
        uploadCount.textContent = activeCount.toString();

        // Add/remove animation class
        if (activeCount > 0) {
            this.widget.classList.add("has-active");
        } else {
            this.widget.classList.remove("has-active");
        }
    }

    updateWidgetVisibility() {
        if (!this.widget) return;

        if (this.uploads.size === 0) {
            this.widget.classList.add("hidden");
        }
    }

    showWidget() {
        if (!this.widget) return;
        this.widget.classList.remove("hidden");
    }

    closeWidget() {
        // Only close if no active uploads
        let hasActive = false;
        this.uploads.forEach((upload) => {
            if (upload.status === "uploading") hasActive = true;
        });

        if (hasActive) {
            // Don't close, just minimize
            this.toggleMinimize(true);
        } else {
            // Clear all and hide
            this.uploads.clear();
            this.saveToSession();
            this.widget.classList.add("hidden");
        }
    }

    toggleMinimize(forceMinimize = null) {
        if (forceMinimize !== null) {
            this.isMinimized = forceMinimize;
        } else {
            this.isMinimized = !this.isMinimized;
        }

        if (this.isMinimized) {
            this.widget.classList.add("minimized");
        } else {
            this.widget.classList.remove("minimized");
        }
    }
}

// Create global instance
window.uploadProgressManager = new UploadProgressManager();
