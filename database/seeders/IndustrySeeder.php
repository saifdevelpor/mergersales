<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Industry;
use App\Models\SubIndustry;
use Illuminate\Support\Str;

class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        $industries = [
            'Financial Services & Investment' => [
                'Accounting', 'Banking', 'Capital Markets', 'Financial Services', 'Insurance',
                'Investment Banking', 'Investment Management', 'Venture Capital & Private Equity',
                'Commercial Real Estate', 'Real Estate'
            ],

            'Technology & Software' => [
                'Computer Software', 'Computer Hardware', 'Computer Networking', 'Computer & Network Security',
                'Information Technology & Services', 'Internet', 'Online Media', 'Wireless',
                'Telecommunications', 'Semiconductors', 'Nanotechnology', 'Information Services'
            ],

            'Industrial, Manufacturing & Engineering' => [
                'Industrial Automation', 'Electrical/Electronic Manufacturing', 'Mechanical or Industrial Engineering',
                'Machinery', 'Mining & Metals', 'Chemicals', 'Plastics', 'Glass, Ceramics & Concrete',
                'Building Materials', 'Paper & Forest Products', 'Packaging & Containers', 'Textiles',
                'Furniture', 'Printing', 'Railroad Manufacture', 'Shipbuilding', 'Defense & Space', 'Aviation & Aerospace'
            ],

            'Transportation, Logistics & Supply Chain' => [
                'Logistics & Supply Chain', 'Warehousing', 'Package/Freight Delivery', 'Transportation/Trucking/Railroad',
                'Maritime', 'Airlines/Aviation', 'Import & Export'
            ],

            'Healthcare, Pharma & Life Sciences' => [
                'Hospital & Health Care', 'Medical Devices', 'Medical Practice', 'Pharmaceuticals',
                'Biotechnology', 'Mental Health Care', 'Veterinary', 'Alternative Medicine',
                'Health, Wellness & Fitness'
            ],

            'Consumer, Retail & E-commerce' => [
                'Retail', 'Wholesale', 'Consumer Goods', 'Consumer Electronics', 'Consumer Services',
                'Apparel & Fashion', 'Luxury Goods & Jewelry', 'Supermarkets', 'Sporting Goods',
                'Cosmetics', 'Furniture'
            ],

            'Food, Beverage & Agriculture' => [
                'Food & Beverages', 'Food Production', 'Dairy', 'Restaurants', 'Wine & Spirits',
                'Farming', 'Ranching', 'Fishery', 'Tobacco'
            ],

            'Energy, Utilities & Environment' => [
                'Oil & Energy', 'Utilities', 'Renewables & Environment', 'Environmental Services'
            ],

            'Media, Entertainment & Creative' => [
                'Media Production', 'Motion Pictures & Film', 'Music', 'Entertainment', 'Broadcast Media',
                'Publishing', 'Newspapers', 'Photography', 'Fine Art', 'Performing Arts', 'Museums & Institutions',
                'Writing & Editing', 'Graphic Design', 'Design', 'Animation'
            ],

            'Professional Services' => [
                'Legal Services', 'Law Practice', 'Management Consulting', 'Staffing & Recruiting', 'Human Resources',
                'Public Relations & Communications', 'Marketing & Advertising', 'Market Research',
                'Outsourcing/Offshoring', 'Translation & Localization', 'Professional Training & Coaching'
            ],

            'Construction, Property & Facilities' => [
                'Construction', 'Architecture & Planning', 'Facilities Services', 'Real Estate',
                'Commercial Real Estate', 'Building Materials'
            ],

            'Education & Training' => [
                'Education Management', 'E-learning', 'Higher Education', 'Primary/Secondary Education', 'Research'
            ],

            'Government, Public Sector & Non-Profit' => [
                'Government Administration', 'Government Relations', 'Public Policy', 'Public Safety',
                'Military', 'Judiciary', 'Law Enforcement', 'Legislative Office', 'Political Organization',
                'International Affairs', 'Think Tanks', 'Non-profit Organization Management', 'Philanthropy',
                'Civic & Social Organization', 'Religious Institutions', 'Fundraising', 'Program Development'
            ],

            'Travel, Hospitality & Leisure' => [
                'Hospitality', 'Leisure, Travel & Tourism', 'Recreational Facilities & Services', 'Gambling & Casinos',
                'Sports', 'Events Services'
            ],

            'Business Services & Misc' => [
                'Business Supplies & Equipment', 'Security & Investigations', 'Individual & Family Services',
                'Libraries', 'Executive Office'
            ]
        ];

        foreach ($industries as $industryName => $subIndustries) {
            $industrySlug = Str::slug($industryName);

            $industry = Industry::create([
                'name' => $industryName,
                'slug' => $industrySlug
            ]);

            foreach ($subIndustries as $sub) {
                // Make slug globally unique by adding industry slug
                $subSlug = Str::slug($industryName . '-' . $sub);

                SubIndustry::create([
                    'industry_id' => $industry->id,
                    'name' => $sub,
                    'slug' => $subSlug
                ]);
            }
        }

        $this->command->info('Industries and Sub-Industries seeded successfully!');
    }
}
