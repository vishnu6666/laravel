<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\GroupPromocode;
use Carbon\Carbon;

class ExpiredDiscountCodeInactive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ExpiredDiscountCodeInactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command use for expired discount code make Inactive.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $expiredGroupPromocodeId = GroupPromocode::selectRaw('id,endDate')
                                                        ->whereDate('endDate','=', Carbon::tomorrow())
                                                        ->pluck('id')
                                                        ->toArray();
        if(!empty($expiredGroupPromocodeId)){
            GroupPromocode::whereIn('id',  $expiredGroupPromocodeId)->update(['isActive'=> 0]);
        }

    }
}
