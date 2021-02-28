<?php

namespace App\Mail;

use App\Helpers\CommonHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendTeamMemberInvitation extends Mailable
{
    use Queueable, SerializesModels;

    protected $teamMember = '';
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($teamMember)
    {
        $this->teamMember = $teamMember;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $token = CommonHelper::encrypt($this->teamMember['receiverEmail']);
        
        $url = url('register/'. $token);

        switch ($this->teamMember['inviteType'])
        {
            case 'TEAM':
                $subject = 'Team Invitation';
                $view = 'mail.invitations.team.join_team';
                break;

            case 'LEAGUE':
                $subject = 'League Invitation';
                $view = 'mail.invitations.league.join_league';
                break;
        }

        return $this->subject($subject)
        ->with(['teamMember' => $this->teamMember, 'url' => $url])
        ->view($view);
    }
}
