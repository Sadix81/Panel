<?php

namespace Modules\Rating\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;
use Modules\Rating\Models\Rate;

class ProductRating extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'rating:calculate';

    /**
     * The console command description.
     */
    protected $description = 'Calculate and update the average rating for all products.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Starting the product rating calculation...');

        $products_id = Product::all()->pluck('id');

        DB::beginTransaction();
        try {
            foreach ($products_id as $productId) {
                $rateProductChart = Rate::where('product_id', $productId)->get();
                $totalRatings = $rateProductChart->count();
                $firstAverageRating = $totalRatings > 0 ? $rateProductChart->sum('rating') / $totalRatings : 0;
                $finalAverageRating = floor($firstAverageRating); // حدف اعشار

                DB::table('rate_products')
                    ->updateOrInsert(
                        ['product_id' => $productId],
                        ['totalrating' => $finalAverageRating]
                    );
            }

            DB::commit();
            $this->info('Product ratings calculated and updated successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error('An error occurred while calculating product ratings: '.$th->getMessage());
            throw $th;
        }
    }
}
