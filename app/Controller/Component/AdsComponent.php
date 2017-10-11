<?php

class AdsComponent extends Component {
    
    public function getAds ($count = 0) {
        $ads_array = array(
            array(
                'title' => 'ABC Pharma',
                'description' => "After a touch-and-go review from FDA staff, Takeda's inflammatory bowel disease drug had an easier time convincing an agency advisory panel of its safety and efficacy.",
                'image' => '/img/Pharma/1.jpg',
                'link' => "http://abcpharmasl.com/"
            ),
            array(
                'title' => 'DEF Pharma',
                'description' => "After a touch-and-go review from FDA staff, Takeda's inflammatory bowel disease drug had an easier time convincing an agency advisory panel of its safety and efficacy.",
                'image' => '/img/Pharma/2.jpg',
                'link' => "http://defpharma.lookchem.com/"
            ),
            array(
                'title' => 'GHI Pharma',
                'description' => "After a touch-and-go review from FDA staff, Takeda's inflammatory bowel disease drug had an easier time convincing an agency advisory panel of its safety and efficacy.",
                'image' => '/img/Pharma/3.jpg',
                'link' => "http://www.ghipharma.com/pharmai/ghipharmahome.html"
            ),
            array(
                'title' => 'JKL Pharma',
                'description' => "After a touch-and-go review from FDA staff, Takeda's inflammatory bowel disease drug had an easier time convincing an agency advisory panel of its safety and efficacy.",
                'image' => '/img/Pharma/4.jpg',
                'link' => "http://www.jklcompany.com/"
            ),
            array(
                'title' => "John's Pharma",
                'description' => "After a touch-and-go review from FDA staff, Takeda's inflammatory bowel disease drug had an easier time convincing an agency advisory panel of its safety and efficacy.",
                'image' => '/img/Pharma/5.jpg',
                'link' => "http://connect.data.com/directory/company/list/1968395/xyz-pharma?guid=1968395"
            )
        );
        
        $ads = array();
        $keys = array_rand($ads_array, $count);
        
        foreach ($keys as $key) {
            $ads[] = $ads_array[$key];
        }
        
        return $ads;
    }
}

?>
