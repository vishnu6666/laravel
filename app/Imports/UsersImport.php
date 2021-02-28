<?php
  
namespace App\Imports;
  
use App\Model\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;
use App\Mail\SendWelcomeMail;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection , WithHeadingRow
{
    
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            try
            {
                $tempUserEmail = $row['email'];
                $userAvailableCount =  User::where('email',$row['email'])->count();
                if($userAvailableCount == 0)
                {
                
                    $user =  User::create([
                        'name'     => $row['name'],
                        'email'    => $row['email'], 
                        'showPassword' => $row['password'],
                        'password' =>  \bcrypt($row['password']),
                    ]);
        
                    // this command send welcome mail
                    $cmd = 'cd '.base_path().' && php artisan mail:sendWelcomeMail '.$user->id;
                    //\Log::debug($cmd);
                    
                    exec($cmd. ' > /dev/null &');
                    
                    //\Log::debug($user);
                }
                else 
                {
                    return redirect(route('users.index'))->with('error', trans('messages.users.import.duplicate',['email' => $tempUserEmail]));
                }
            }
            catch(\Illuminate\Database\QueryException $ex){ 
                return redirect(route('users.index'))->with('error', trans('messages.users.import.error'));

                // Note any method of class PDOException can be called on $ex.
            }
            catch(Exception $ex){ 
                return redirect(route('users.index'))->with('error',$ex);
                // Note any method of class PDOException can be called on $ex.
            }
        }
    }
}