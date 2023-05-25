<?php

namespace App\Console\Commands;

use App\Models\DeliveryType;
use App\Models\Driver;
use App\Models\DriversCurrentLocation;
use App\Models\Field;
use App\Models\Market;
use App\Models\Order;
use App\Models\User;
use App\Notifications\DriverAssignedNotification;
use App\Notifications\DriverAssignedNotificationToUser;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Kreait\Firebase\Contract\Database;

class AssignDriver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'driver:assign-to-normal-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign drivers to orders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Database $database)
    {
        parent::__construct();

        $this->database = $database;
        $this->table = 'user_locations';
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = Order::with('deliveryType')
            ->where('delivery_type_id', DeliveryType::TYPE_EXPRESS)
            ->whereIn('order_status_id',[Order::STATUS_RECEIVED,
                Order::STATUS_PREPARING, Order::STATUS_READY])
            ->whereIn('type', [Order::PRODUCT_TYPE, Order::ORDER_REQUEST_TYPE])
            ->where('is_order_approved', true)
            ->whereHas('deliveryType', function ($query) {
                $query->where('is_sloted', 0);
            })
            ->whereNull('driver_id')
            ->get();


        foreach ($orders as $order){
            if($order->sector_id != Field::TAKEAWAY){

                $market = Market::where('id', $order->market_id)->first();

                $latMarket = $market->latitude;
                $longMarket = $market->longitude;

                $references = $this->database->getReference($this->table)->getValue();

                foreach ($references as $reference){

                    if (array_key_exists("user_id", $reference)){

                        $currentDriverLatitude = $reference['latitude'];
                        $currentDriverLongitude = $reference['longitude'];

                        if(DriversCurrentLocation::getDriverCurrentLocations($latMarket, $longMarket, $currentDriverLatitude,
                                $currentDriverLongitude, "K") < 10) {

                            $drivers = Driver::where('available',1)
                                ->where('user_id',  $reference['user_id'])
                                ->get();

                            if(count($drivers) > 0){
                                foreach ($drivers as $driver) {
                                    $driverId = $driver->id;
                                    DriversCurrentLocation::updateCurrentLocation($driverId,
                                        $currentDriverLatitude, $currentDriverLongitude);
                                }
                            }
                        }
                    }

                }

                $driversCurrentLocations = DriversCurrentLocation::getAvailableDriver(
                    $latMarket, $longMarket, $market
                );

                if($driversCurrentLocations){

                    $this->updateDriverToOrder($order, $driversCurrentLocations);
                }
                else{

                    if($order->created_at < Carbon::now()->subMinutes(3)){
                        $driversCurrentLocations = DriversCurrentLocation::getAvailableDriver($latMarket, $longMarket, null);

                        if($driversCurrentLocations){

                            $this->updateDriverToOrder($order, $driversCurrentLocations);
                        }
                    }
                }

            }
        }
    }

    public function updateDriverToOrder($order, $driversCurrentLocations)
    {
        $driver = Driver::where('id', $driversCurrentLocations->driver_id)->first();
//        $distance = $order->distance;

//        if ($distance <= $driver->base_distance) {
//            $driverCommissionAmount = $driver->delivery_fee;
//        }
//        else {
//            $additionalDistance = $order->distance - $driver->base_distance;
//            $driverCommissionAmount = $driver->delivery_fee + $additionalDistance * $driver->additional_amount;
//        }

        $order->order_status_id = Order::STATUS_DRIVER_ASSIGNED;
        $order->driver_id = $driversCurrentLocations->driver->user_id;
        $order->driver_assigned_at = Carbon::now();
//        $order->driver_commission_amount = $driverCommissionAmount;
        $order->save();

        if($driver){
            $driver->available = 0;
            $driver->save();

            Order::driverNotification($driver, $order->id);
            Order::shippedNotification($order->id);
        }
    }
}
