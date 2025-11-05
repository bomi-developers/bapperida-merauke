<?php

namespace App\Http\Controllers;

use App\Models\LendingPage;
use App\Models\Template;
use Illuminate\Http\Request;

class LendingPageController extends Controller
{
    public function index()
    {
        $pages = LendingPage::with('template')
            ->orderBy('order', 'asc')
            ->get();

        return view('pages.lending_page.index', compact('pages'));
    }
    public function template()
    {
        $templates = Template::all();
        return view('pages.lending_page.template', compact('templates'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_template' => 'required|exists:template,id',
        ]);
        $maxOrder = LendingPage::max('order') ?? 0;
        $page = LendingPage::create([
            'id_template' => $request->id_template,
            'order' => $request->order  ?? ($maxOrder + 1),
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lending page section added successfully.',
            'data' => $page
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_template' => 'required|exists:template,id',
            'order' => 'required|integer',
        ]);

        $page = LendingPage::findOrFail($id);
        $page->update([
            'id_template' => $request->id_template,
            'order' => $request->order,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lending page updated successfully.',
            'data' => $page
        ]);
    }

    public function destroy($id)
    {
        $page = LendingPage::findOrFail($id);
        $page->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lending page deleted successfully.'
        ]);
    }
}