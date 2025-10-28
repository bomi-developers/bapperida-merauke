<?php

namespace App\Mail;

use App\Models\ProposalInovasi;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProposalInovasiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $proposal;

    public function __construct(ProposalInovasi $proposal)
    {
        $this->proposal = $proposal;
    }

    public function build()
    {
        return $this->subject('Konfirmasi Pengajuan Proposal Inovasi')
            ->markdown('emails.proposal_inovasi');
    }
}
