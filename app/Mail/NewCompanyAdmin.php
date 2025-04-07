<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewCompanyAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $company;
    public $password;

    public function __construct($user, $company, $password)
    {
        $this->user = $user;
        $this->company = $company;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Akun Admin HRIS Anda')
                    ->view('emails.new-company');
    }
}
