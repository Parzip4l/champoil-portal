<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class PayslipEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $pdfPath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($employee, $pdfPath)
    {
        $this->employee = $employee;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Payslip')
                    ->view('emails.payslip')
                    ->attach($this->pdfPath, [
                        'as' => 'payslip.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}
