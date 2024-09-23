<?php

namespace App\Mail\Koperasi;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengajuanPinjamanReject extends Mailable
{
    use Queueable, SerializesModels;
    public $EmailData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($EmailData)
    {
        $this->employee = $EmailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Membership Loan Rejected')
                    ->view('emails.reject-anggota')
                    ->with([
                        'employeeName' => strtoupper($this->employee->nama),
                        'employee' => $this->employee,
                    ]);
    }
}
