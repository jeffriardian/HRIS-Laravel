<?php

namespace Modules\Production\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\Production\Models\Machine as Model;

class MachineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();
        $data = [
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'TSH 13110','area' => '1A','year' => 'November 2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'TSH 13109','area' => '2A','year' => 'November 2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'TSH 13108','area' => '3A','year' => 'November 2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'TSH 13088','area' => '4A','year' => 'August 2013', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'TSH 13089','area' => '5A','year' => 'August 2013', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'TSH 12127','area' => '6A','year' => 'June 2013', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'S 15513','area' => '1B','year' => 'February 2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'S 15514','area' => '2B','year' => 'February 2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'S 15511','area' => '3B','year' => 'February 2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'S 15515','area' => '4B','year' => 'February 2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'S 15512','area' => '5B','year' => 'February 2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'S 15516','area' => '6B','year' => 'February 2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'S 15506','area' => '7B','year' => 'January 2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'S 15507','area' => '8B','year' => 'January 2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'S 15505','area' => '9B','year' => 'January 2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'S 15509','area' => '10B','year' => 'January 2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'S 15504','area' => '11B','year' => 'January 2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'S 15508','area' => '12B','year' => 'January 2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'K 15531','area' => '13B','year' => 'May 2015', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'K 15532','area' => '14B','year' => 'May 2015', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'K 15530','area' => '15B','year' => 'May 2015', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'K 15534','area' => '16B','year' => 'May 2015', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'K 15535','area' => '17B','year' => 'May 2015', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'K 15526','area' => '18B','year' => 'May 2015', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'TSH 14049','area' => '1C','year' => 'March 2014', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'TSH 14018','area' => '2C','year' => 'March 2014', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'25', 'machine_type_id'=>0, 'code'=>'3211 HF','area' => '3C','year' => '2013', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'K 15528','area' => '4C','year' => 'May 2015', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'29', 'machine_type_id'=>'24', 'code'=>'CELUP - 5C','area' => '5C','year' => '2008', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'SM 17188','area' => '6C','year' => '2017', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'SM 17189','area' => '7C','year' => '2017', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'SM 17190','area' => '8C','year' => '2017', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'SM 17191','area' => '9C','year' => '2017', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'K 15527','area' => '10C','year' => '2015', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'SM 17192','area' => '11C','year' => '2017', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'SM 17193','area' => '12C','year' => '2017', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'SM 17203','area' => '13C','year' => '2018', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'S 16700','area' => '14C','year' => '2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'S 16701','area' => '15C','year' => '2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'S 16702','area' => '16C','year' => '2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'S 16703','area' => '17C','year' => '2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'1', 'machine_type_id'=>'49', 'code'=>'1455','area' => '1D','year' => '2005', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'13', 'code'=>'19052','area' => '2D','year' => '1989', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'17', 'code'=>'30895','area' => '3D','year' => '1996', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'15', 'code'=>'2984','area' => '4D','year' => '1995', 'is_active' => 0, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'13', 'code'=>'29664','area' => '5D','year' => '1995', 'is_active' => 0, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'13', 'code'=>'21532','area' => '6D','year' => '1989', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'13', 'code'=>'19465','area' => '7D','year' => '1988', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'13', 'code'=>'CELUP - 8D','area' => '8D','year' => '1995', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'1', 'machine_type_id'=>'20', 'code'=>'CELUP - 9D','area' => '9D','year' => '2003', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'1', 'machine_type_id'=>'20', 'code'=>'21119','area' => '10D','year' => '2003', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'TSH 14016','area' => '11D','year' => 'March 2014', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'TSH 14017','area' => '12D','year' => 'March 2014', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'S 16706','area' => '13D','year' => '2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'S 16707','area' => '14D','year' => '2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'K 15530','area' => '15D','year' => 'May 2015', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'1', 'code'=>'K 15529','area' => '16D','year' => 'May 2015', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'13', 'code'=>'26344','area' => '17D','year' => '1993', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'S 16704','area' => '18D','year' => '2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'S 16705','area' => '19D','year' => '2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'1', 'machine_type_id'=>'21', 'code'=>'CELUP - 20D','area' => '20D','year' => '2005', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'1', 'machine_type_id'=>'22', 'code'=>'21146','area' => '1E','year' => '2008', 'is_active' => 0, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'1', 'machine_type_id'=>'22', 'code'=>'21134','area' => '2E','year' => '2008', 'is_active' => 0, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'13', 'code'=>'28017','area' => '3E','year' => '1994', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'13', 'code'=>'28019','area' => '4E','year' => '1994', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'13', 'code'=>'24840','area' => '5E','year' => '1992', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'13', 'code'=>'24850','area' => '6E','year' => '1992', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'13', 'code'=>'27054','area' => '7E','year' => '1993', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'13', 'code'=>'26593','area' => '8E','year' => '1993', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'13', 'code'=>'29891','area' => '9E','year' => '1995', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'27', 'machine_type_id'=>'23', 'code'=>'69934','area' => '10E','year' => '1996', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'17', 'machine_type_id'=>0, 'code'=>'CELUP - 11E','area' => '11E','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'14', 'code'=>'28514','area' => '12E','year' => '1990', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'12', 'code'=>'22616','area' => '13E','year' => '1990', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'12', 'code'=>'23241','area' => '14E','year' => '1990', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'13', 'code'=>'22190','area' => '1F','year' => '1990', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'13', 'code'=>'22167','area' => '2F','year' => '1990', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'13', 'code'=>'22164','area' => '3F','year' => '1990', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'25', 'machine_type_id'=>0, 'code'=>'CELUP - 4F','area' => '4F','year' => '-', 'is_active' => 0, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'7', 'machine_type_id'=>'2', 'code'=>'S 15517','area' => '5F','year' => '2016', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'16', 'code'=>'49604','area' => '6F','year' => '2006', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'13', 'code'=>'28011','area' => '7F','year' => '1994', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'28', 'machine_type_id'=>'13', 'code'=>'25775','area' => '8F','year' => '1992', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'17', 'machine_type_id'=>0, 'code'=>'CELUP - 9F','area' => '9F','year' => '2002', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'17', 'machine_type_id'=>0, 'code'=>'CELUP - 10F','area' => '10F','year' => '2002', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'17', 'machine_type_id'=>0, 'code'=>'CELUP - 11F','area' => '11F','year' => '2002', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'1','machine_brand_id'=>'17', 'machine_type_id'=>0, 'code'=>'CELUP - 12F','area' => '12F','year' => '2004', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'13','machine_brand_id'=>'4', 'machine_type_id'=>'32', 'code'=>'APTQP00157E0495','area' => 'BIANCO 1','year' => '2012', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'13','machine_brand_id'=>'4', 'machine_type_id'=>'32', 'code'=>'APTQP00157E0496','area' => 'BIANCO 2','year' => '2012', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'13','machine_brand_id'=>'4', 'machine_type_id'=>'33', 'code'=>'APTWP00306K1065','area' => 'BIANCO 3','year' => '2018', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'13','machine_brand_id'=>'24', 'machine_type_id'=>'9', 'code'=>'4418','area' => 'SANTEX 4','year' => '1997', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'13','machine_brand_id'=>'24', 'machine_type_id'=>'10', 'code'=>'6187','area' => 'SANTEX 5','year' => '2005', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'14','machine_brand_id'=>'24', 'machine_type_id'=>'9', 'code'=>'6188','area' => 'SANTEX 1','year' => '2005', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'14','machine_brand_id'=>'24', 'machine_type_id'=>'10', 'code'=>'5237','area' => 'SANTEX 2','year' => '2001', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'14','machine_brand_id'=>'6', 'machine_type_id'=>'34', 'code'=>'801564','area' => 'BRUCKNER 3','year' => '2008', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'14','machine_brand_id'=>'24', 'machine_type_id'=>'8', 'code'=>'5608','area' => 'SANTEX CONTINOUS','year' => '2002', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'15','machine_brand_id'=>'24', 'machine_type_id'=>'8', 'code'=>'6183','area' => 'SANTEX 1','year' => '2005', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'15','machine_brand_id'=>'24', 'machine_type_id'=>'9', 'code'=>'6186','area' => 'SANTEX 2','year' => '2005', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'15','machine_brand_id'=>'24', 'machine_type_id'=>'9', 'code'=>'6182','area' => 'SANTEX 3','year' => '2004', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'15','machine_brand_id'=>'24', 'machine_type_id'=>'9', 'code'=>'6185','area' => 'SANTEX 4','year' => '2004', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'16','machine_brand_id'=>'4', 'machine_type_id'=>'35', 'code'=>'CPTAP0031J1135','area' => 'L1','year' => '2017', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'16','machine_brand_id'=>'18', 'machine_type_id'=>'38', 'code'=>'10 KST 2967','area' => 'L2','year' => '2010', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'16','machine_brand_id'=>'18', 'machine_type_id'=>'38', 'code'=>'10 KST 3098','area' => 'L3','year' => '2012', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'16','machine_brand_id'=>'18', 'machine_type_id'=>'38', 'code'=>'10 KST 3229','area' => 'L4','year' => '2012', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'16','machine_brand_id'=>'18', 'machine_type_id'=>'38', 'code'=>'10 KST 2943','area' => 'L5','year' => '2010', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'17','machine_brand_id'=>'18', 'machine_type_id'=>'39', 'code'=>'5016 T','area' => 'L1','year' => '2006', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'17','machine_brand_id'=>'23', 'machine_type_id'=>'7', 'code'=>'6222','area' => 'S2','year' => '2005', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'17','machine_brand_id'=>'18', 'machine_type_id'=>'38', 'code'=>'10 KST 2379','area' => 'L3','year' => '2008', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'17','machine_brand_id'=>'18', 'machine_type_id'=>'38', 'code'=>'10 KST 2495','area' => 'L4','year' => '2008', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'17','machine_brand_id'=>'18', 'machine_type_id'=>'38', 'code'=>'10 KST 2379','area' => 'L5','year' => '2007', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'17','machine_brand_id'=>'13', 'machine_type_id'=>'40', 'code'=>'3199','area' => 'L6','year' => '1998', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'19','machine_brand_id'=>'4', 'machine_type_id'=>'42', 'code'=>'970931','area' => '1','year' => '1998', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'19','machine_brand_id'=>'4', 'machine_type_id'=>'43', 'code'=>'RDCC P00264 D0903','area' => '2','year' => '2012', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'19','machine_brand_id'=> '11', 'machine_type_id'=>'44', 'code'=>'EH12-0004','area' => '3','year' => '2012', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'19','machine_brand_id'=>'18', 'machine_type_id'=>'37', 'code'=>'2600','area' => '4','year' => '2008', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'3','machine_brand_id'=>'15', 'machine_type_id'=>'4', 'code'=>'SMM - 01','area' => '1','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'3','machine_brand_id'=>'15', 'machine_type_id'=>'4', 'code'=>'SMM - 02','area' => '2','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'3','machine_brand_id'=>'15', 'machine_type_id'=>'4', 'code'=>'SMM - 03','area' => '3','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'3','machine_brand_id'=>'15', 'machine_type_id'=>'4', 'code'=>'SMM - 04','area' => '4','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'3','machine_brand_id'=>'15', 'machine_type_id'=>'4', 'code'=>'SMM - 05','area' => '5','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'3','machine_brand_id'=>'15', 'machine_type_id'=>'4', 'code'=>'SMM - 06','area' => '6','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'3','machine_brand_id'=>'15', 'machine_type_id'=>'4', 'code'=>'SMM - 07','area' => '7','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'3','machine_brand_id'=>'15', 'machine_type_id'=>'4', 'code'=>'SMM - 08','area' => '8','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'3','machine_brand_id'=>'15', 'machine_type_id'=>'4', 'code'=>'SMM - 09','area' => '9','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'3','machine_brand_id'=>'15', 'machine_type_id'=>'4', 'code'=>'SMM - 10','area' => '10','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'3','machine_brand_id'=>'15', 'machine_type_id'=>'4', 'code'=>'SMM - 11','area' => '11','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'3','machine_brand_id'=>'15', 'machine_type_id'=>'4', 'code'=>'SMM - 12','area' => '12','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'3','machine_brand_id'=>'15', 'machine_type_id'=>'4', 'code'=>'SMM - 13','area' => '13','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'3','machine_brand_id'=>'15', 'machine_type_id'=>'4', 'code'=>'SMM - 14','area' => '14','year' => '2019', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'3','machine_brand_id'=>'15', 'machine_type_id'=>'4', 'code'=>'SMM - 15','area' => '15','year' => '2019', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'3','machine_brand_id'=>'15', 'machine_type_id'=>'4', 'code'=>'SMM - 16','area' => '16','year' => '2019', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'5','machine_brand_id'=>'20', 'machine_type_id'=>0, 'code'=>'NETRAL BELAKANG - 01','area' => '1','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'2','machine_brand_id'=>'12', 'machine_type_id'=>'18', 'code'=>'3390500','area' => '1','year' => '2005', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'2','machine_brand_id'=>'12', 'machine_type_id'=>'18', 'code'=>'3.4007.00','area' => '2','year' => '2009', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'2','machine_brand_id'=>'24', 'machine_type_id'=>'6', 'code'=>'5607','area' => '3','year' => '2002', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'2','machine_brand_id'=>'24', 'machine_type_id'=>'8', 'code'=>'5608','area' => '4','year' => '2002', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'4','machine_brand_id'=>'19', 'machine_type_id'=>0, 'code'=>'ROLL - 01','area' => '1','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'6','machine_brand_id'=>'10', 'machine_type_id'=>'3', 'code'=>'100445','area' => '1','year' => '2006', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'6','machine_brand_id'=>'10', 'machine_type_id'=>'25', 'code'=>'100977','area' => '2','year' => '1989', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'6','machine_brand_id'=>'10', 'machine_type_id'=>'26', 'code'=>'100989','area' => '3','year' => '1989', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'6','machine_brand_id'=>'10', 'machine_type_id'=>'27', 'code'=>'MERCER - 04','area' => '4','year' => '1990', 'is_active' => 0, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'6','machine_brand_id'=>'10', 'machine_type_id'=>0, 'code'=>'MERCER - 05','area' => '5','year' => '-', 'is_active' => 0, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'6','machine_brand_id'=>'10', 'machine_type_id'=>'27', 'code'=>'100099','area' => '6','year' => '1992', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'6','machine_brand_id'=>'10', 'machine_type_id'=>'30', 'code'=>'7','area' => '7','year' => '1992', 'is_active' => 0, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'6','machine_brand_id'=>'10', 'machine_type_id'=>'19', 'code'=>'4085','area' => '8','year' => '1997', 'is_active' => 0, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'6','machine_brand_id'=>'22', 'machine_type_id'=>0, 'code'=>'MERCER - 09','area' => '9','year' => '-', 'is_active' => 0, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'7','machine_brand_id'=>'9', 'machine_type_id'=>'28', 'code'=>'S - 01','area' => '1','year' => '2015', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'7','machine_brand_id'=>'9', 'machine_type_id'=>'29', 'code'=>'S - 02','area' => '2','year' => '2015', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'7','machine_brand_id'=>'9', 'machine_type_id'=>'29', 'code'=>'S - 03','area' => '3','year' => '2015', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'7','machine_brand_id'=>'26', 'machine_type_id'=>'31', 'code'=>'S - 04','area' => '4','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'7','machine_brand_id'=>'5', 'machine_type_id'=>0, 'code'=>'S - 05','area' => '5','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'8','machine_brand_id'=>'24', 'machine_type_id'=>'5', 'code'=>'6190','area' => '1','year' => '2005', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'8','machine_brand_id'=>'24', 'machine_type_id'=>'5', 'code'=>'6189','area' => '2','year' => '2006', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'9','machine_brand_id'=>'16', 'machine_type_id'=>0, 'code'=>'INTEX - 01','area' => '1','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'9','machine_brand_id'=>'16', 'machine_type_id'=>0, 'code'=>'INTEX - 02','area' => '2','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'9','machine_brand_id'=>'16', 'machine_type_id'=>0, 'code'=>'INTEX - 03','area' => '1','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'11','machine_brand_id'=>'2', 'machine_type_id'=>0, 'code'=>'BALIK KAIN - 01','area' => '1','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'10','machine_brand_id'=>'8', 'machine_type_id'=>'46', 'code'=>'CYLINDER - 01','area' => '1','year' => '-', 'is_active' => 0, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'10','machine_brand_id'=>'8', 'machine_type_id'=>'46', 'code'=>'CYLINDER - 02','area' => '2','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'10','machine_brand_id'=>'8', 'machine_type_id'=>'46', 'code'=>'CYLINDER - 03','area' => '3','year' => '-', 'is_active' => 0, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'10','machine_brand_id'=>'8', 'machine_type_id'=>'46', 'code'=>'CYLINDER - 04','area' => '4','year' => '-', 'is_active' => 0, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'10','machine_brand_id'=>'4', 'machine_type_id'=>'11', 'code'=>'HYGAP00296K0418','area' => '5','year' => '2017', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'18','machine_brand_id'=>'14', 'machine_type_id'=>0, 'code'=>'HASPEL - 01','area' => '1','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'18','machine_brand_id'=>'14', 'machine_type_id'=>'50', 'code'=>'15138','area' => '2','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'5','machine_brand_id'=>'20', 'machine_type_id'=>0, 'code'=>'NETRAL - 01','area' => '1','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'5','machine_brand_id'=>'20', 'machine_type_id'=>0, 'code'=>'NETRAL - 02','area' => '2','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'20','machine_brand_id'=>'21', 'machine_type_id'=>'36', 'code'=>'10 KSA 02516','area' => '1','year' => '2008', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'20','machine_brand_id'=>'11', 'machine_type_id'=>'47', 'code'=>'742','area' => '2','year' => '1988', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'21','machine_brand_id'=>'11', 'machine_type_id'=>'45', 'code'=>'EH 12 - 00004 8 CH','area' => '1','year' => '2012', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'21','machine_brand_id'=>'4', 'machine_type_id'=>'43', 'code'=>'RDCCP 00264D0903','area' => '2','year' => '2012', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'12','machine_brand_id'=>'3', 'machine_type_id'=>0, 'code'=>'BELAH KAIN - 01','area' => '1','year' => '-', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['machine_group_id'=>'12','machine_brand_id'=>'4', 'machine_type_id'=>'41', 'code'=>'970991','area' => '2','year' => '1998', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
        ];
        
        Model::insert($data);
    }

    public function read()
    {
        $file = public_path('machines.csv');

        $models = $this->csvToArray($file, ';');

        foreach($models as $model){
            $machineBrand = \Modules\Production\Models\MachineBrand::where('name', $model['brand'])->first();
            $machineType = \Modules\Production\Models\MachineType::where('name', $model['type'])->first();
            echo "['machine_brand_id'=>'".($machineBrand ? $machineBrand->id : '')."', 'machine_type_id'=>'".($machineType ? $machineType->id : '')."', 'code'=>'".$model['code']."','area' => '".$model['area']."','year' => '".$model['year']."', 'is_active' => 1, 'created_by'=>1,'updated_by'=>1,'created_at'=>\$now,'updated_at'=>\$now],\n";
        }
    }

    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
}
