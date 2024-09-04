<?php

namespace App\Mail\Koperasi;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengajuanAnggotaReject extends Mailable
{
    use Queueable, SerializesModels;
    public $employee;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($employee)
    {
        $this->employee = $employee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Membership Rejected')
                    ->view('emails.reject-anggota')
                    ->with([
                        'employeeName' => strtoupper($this->employee->nama),
                        'employee' => $this->employee,
                    ]);
    }
}
