<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use App\Employee;

class NewEmployee extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $company;

    /**
     * Create a new message instance.
     *
     * @param $employee
     * @return void
     */
    public function __construct($employee)
    {
        $this->employee = $employee;

        // Memastikan user sudah terautentikasi
        if (Auth::check()) {
            $code = Auth::user()->employee_code;
            $this->company = Employee::where('nik', $code)->first();
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Company On Boarding')
                    ->view('emails.email-test')
                    ->with([
                        'employee' => $this->employee,
                        'company' => $this->company,
                    ]);
    }
}
