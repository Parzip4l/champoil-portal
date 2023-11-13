<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PayslipEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $dataPayslip;
    public $pdfPath;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dataPayslip,$pdfPath)
    {
        $this->dataPayslip = $dataPayslip;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Payslip Email',
        );
    }

    public function content()
    {
        return new Content(
            view: 'pages.hc.payrol.payslip-file',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }

    public function build()
    {
        return $this->view('pages.hc.payrol.payslip-file') // View Payslip
            ->subject('Payslip');
            attach($this->pdfPath, ['as' => 'slip_gaji.pdf', 'mime' => 'application/pdf']);
    }
}
