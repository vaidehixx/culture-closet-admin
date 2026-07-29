<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::truncate();

        $structure = [
            'By Occasion' => ['Partywear','Cocktail','Mehendi','Engagement','Festive Wear','Bridal'],
            'By Ethnic Wear' => [
                'Salwar Kameez','Anarkali Suits','Churidar','Sharara Suits',
                'Palazzo Suits','Punjabi Suits','Gharara Suits',
                'Sarees','Lehengas','Kurta','Kurti','Leggings','Ready Made Blouses',
            ],
        ];

        $order = 1;
        foreach ($structure as $parentName => $children) {
            $parent = Category::create([
                'parent_id'  => null,
                'name'       => $parentName,
                'slug'       => Str::slug($parentName),
                'is_active'  => true,
                'sort_order' => $order++,
            ]);
            $childOrder = 1;
            foreach ($children as $childName) {
                Category::create([
                    'parent_id'  => $parent->id,
                    'name'       => $childName,
                    'slug'       => Str::slug($childName),
                    'is_active'  => true,
                    'sort_order' => $childOrder++,
                ]);
            }
        }

        // Saree sub-types (children of Sarees)
        $sarees = Category::where('name','Sarees')->first();
        if ($sarees) {
            foreach (['Casual Sarees','Partywear Sarees'] as $i => $name) {
                Category::create(['parent_id'=>$sarees->id,'name'=>$name,'slug'=>Str::slug($name),'is_active'=>true,'sort_order'=>$i+1]);
            }
        }

        // Lehenga occasion sub-types
        $lehengas = Category::where('name','Lehengas')->first();
        if ($lehengas) {
            foreach (['Partywear Lehenga','Cocktail Lehenga','Mehendi Lehenga','Engagement Lehenga','Festive Lehenga','Bridal Lehenga'] as $i => $name) {
                Category::create(['parent_id'=>$lehengas->id,'name'=>$name,'slug'=>Str::slug($name),'is_active'=>true,'sort_order'=>$i+1]);
            }
        }
    }
}
