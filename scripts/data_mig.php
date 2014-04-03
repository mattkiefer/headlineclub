<?php

// START CONFIGURATION

require('./creds.php');

// get data?
$get_d = False;

// transform data?
$trans_d = True;

// write to wp?
$write_wp = True;

// connection to wp db
$wp_conn = mysql_connect('localhost',$creds['wdb'],$creds['wpw']);

// data dump raw file
$file_path="./jdata.csv";

// transformed data file
$transform_path="./jdata_trans.csv";

// assign category names, search terms to ids
$cats = array(
            array(
                'id' => NULL,
                'name' => 'JobFile',
                'sterm' => 'jobfile',),
            array(
                'id' => NULL,
                'name' => 'Lisagor',
                'sterm' => 'lisagor',),
            array(
                'id' => NULL,
                'name' => 'FOIA Fest',
                'sterm' => 'foia fest',),
            array(
                'id' => NULL,
                'name' => 'Burger Nite',
                'sterm' => 'burger',),
            array(
                'id' => NULL,
                'name' => 'Minutes',
                'sterm' => 'minutes',)
); 

$whoami = exec('whoami');
// config this
$dir = 'chc/';
$path = '/home/' . $whoami . '/public_html/' . $dir;

update_wp_db($wp_conn);

// END CONFIGURATION



// get joomla dump
get_data($get_d,$file_path,$creds);

// transform it
trans_data($trans_d, $file_path, $transform_path);

// delete and reinit wp categories
require($path . 'wp-load.php');
require($path . 'wp-admin/includes/taxonomy.php');
$cats = create_cats($write_wp, $cats);

// delete old posts before importing
delete_wp_posts($write_wp, $wp_conn);

// write to wordpress
write_to_wp($write_wp, $transform_path, $cats);




function get_data($get, $file_path,$creds) {
    echo 'get_data()';
    if ($get == True) {
        // get connection
        $j_conn = mysql_connect('localhost',$creds['jdb'],$creds['jpw']);
        $j_db = mysql_select_db($creds['jdb'],$j_conn);       
 
        // report success 
        if (!$j_conn) {
            die ('could not connect: ' . mysql_error());
        } 	
        echo 'Connected successfully!';

        // execute query
	$sql = mysql_query("select * from mccg_content where state=1");

	// field headers
	$columns_total = mysql_num_fields($sql);
	$headers = array(); 
        for ($i = 0; $i < $columns_total; $i++) {     
            $headers[] = mysql_field_name($sql, $i); 
        } 

        // open output file
	$file = fopen($file_path,'w');
	header('Content-type: text/csv');
	header('Content-Disposition: attachment; filename='.$file);
        
        // get data from query into file
        fputcsv($file,$headers);
        while ($row = mysql_fetch_row($sql)) {
            fputcsv($file,array_values($row));
        }
        mysql_close($j_conn);
    }
}



function trans_data($trans, $file_path, $transform_path) {
    // transform so that one line = one record
    echo 'trans_data()';
    if ($trans == True) { 
	// start by loading file into string and destroying all line breaks in one pass
	$file_str = file_get_contents($file_path);
	$transform_file_str = str_replace("\n","",$file_str); 
	
        // then replace line breaks in recognized eol patterns
	$eol = 'author="';
	$transform_file_str = str_replace($eol,$eol . "\n",$transform_file_str);
	$transform_file_str = str_replace("metadata2","metadata\n2",$transform_file_str);

        /*
        $scrub = array('"' => chr(0x93),
                      );  

        foreach ($scrub as $replace => $search) {
            $transform_file_str = str_replace($search, $replace, $transform_file_str); 
        }
        */
	// clean up for ms chars
	//$transform_file_str = iconv('latin1', 'ASCII//TRANSLIT', $transform_file_str); 
	}  
	// put the string back in transformed file
	file_put_contents($transform_path,$transform_file_str); 
}


function bad_chars($data) {
        $replace_find = array(
                              chr(0x85) => '-',
                              chr(0x91) => '\'',
                              chr(0x92) => '\'',
			      chr(0x94) => '"',
                              chr(0x93) => '"',
                              chr(0x96) => '',
                              chr(0x97) => '--',
                             );
        foreach ($replace_find as $find => $replace) {
            $data = str_replace($find, $replace, $data);
        }
        //$data = iconv('latin1', 'ASCII//TRANSLIT', $data);
        $data = iconv('latin1', 'UTF-8', $data);
        return $data;
}

function delete_wp_posts($write_wp, $wp_conn) {
	if ($write_wp == True) {
		// delete from wp content table
		// get connection
		echo 'delete_wp_posts()';
		$wp_db = mysql_select_db($creds['wdb'],$wp_conn);       

		// report success 
		if (!$wp_conn) {
		    die ('could not connect: ' . mysql_error());
		} 	
		echo 'Connected successfully!';

		echo 'Deleting previous WP migration posts';
		// delete zero author (non-UI) posts and close
		$sql = mysql_query("delete from iug_posts where post_author = 0");
		mysql_close($wp_conn);
	}
}

function create_cats($write_wp, $cats) {
    if ($write_wp == True) {
	// delete from cat table all non-Uncategorized cats
        $args = array(
            'hide_empty' => 0,
            'exclude' => 1,
        );

	$old_cats = get_categories($args);
	foreach ($old_cats as $old_cat) {
	    $old_cat_id = $old_cat->cat_ID;
	    wp_delete_category($old_cat_id);
	}

	// for each item in $cats array
	// insert item, name into cat table
        // retrieve id and replace cat object in new array
        $new_cats = array();
	foreach ($cats as $cat) {
	    $cat_arr = array(
	        'cat_name' => $cat['name'],
	    );
            $cat_id = wp_insert_category($cat_arr);
            $cat['id'] = $cat_id;
	    $new_cats[] = $cat;
        }
        //var_dump($new_cats);
    }
    return $new_cats;
}


function wordlimit($string, $length = 50, $ellipsis = "...") { 
	   $string = strip_tags($string, '<img>');
	   $words = explode(' ', $string); 
	   if (count($words) > $length) 
	       return implode(' ', array_slice($words, 0, $length)) . $ellipsis; 
	   else 
	       return $string; 
} 

function img_src($text) {
    $old_img = 'src="images/';
    $new_img = 'src="http://headlineclub.org/images/';
    $text = str_replace($old_img,$new_img,$text);
    return $text;
}


function cat_array($fulltext, $cats) {
    // loop through each category array and see which search terms match
    $cat_list = array();
    foreach ($cats as $cat) {
        if (stristr($fulltext,$cat['sterm']) !== False) {
	    $cat_list[] = $cat['id'];
        }
    }
    return $cat_list;
}   




function write_to_wp($write, $transform_path, $cats) {  
    echo 'write_to_wp()';
    if ($write == True) {
        
        //require('/home3/matthhf3/public_html/chc/wp-blog-header.php');

	// put data into wp via api
	echo "READING CSV TO WP API";
	//require('/home3/matthhf3/public_html/chc/wp-blog-header.php');

	// read csv into associative array

	$count = 1;
	$header = NULL;
	$data = array();
	$csv = fopen($transform_path,'r');
	while (!feof($csv)) {
	    $row = fgetcsv($csv);
            // get headers
	    if (!$header) $header = $row;
	    // get data
	    else $data = array_combine($header,$row);
	    $clean_data = array();

            foreach($data as $key => $datum) {
                $datum = bad_chars($datum);
                $clean_data[$key] = $datum;
            }
            $data = $clean_data;
            //if ($count==4) die;
            // enter wordpress
	    // joomla headers for reference: 
	    // id,title,alias,title_alias,introtext,fulltext,state,sectionid,mask,catid,created,created_by,created_by_alias,modified,modified_by,checked_out,checked_out_time,publish_up,publish_down,images,urls,attribs,version,parentid,ordering,metakey,metadesc,access,hits,metadata
	   
	    // fulltext and introtext were used interchangeably in joomla
	    // so use introtext if it's longer.
	    if (strlen($data["introtext"]) > strlen($data["fulltext"])){
		$fulltext = $data["introtext"];
	    } else {
		$fulltext = $data["fulltext"];
	    }
            $alltext = $fulltext . $data["title"];    
	    $introtext = wordlimit($fulltext);
            $introtext = img_src($introtext);
            $fulltext = img_src($fulltext);     
	    // todo strip html, except maybe first image?
	    $cat_array = cat_array($alltext, $cats);
	    $post = array(
		// 'ID' => $data['id']
		'post_content' => $fulltext,
		'post_title' => $data["title"],
		'post_date' => $data["created"],
		'post_status' => "publish",
		'post_excerpt' => $introtext,
		'post_category' => $cat_array,
	    );
	    wp_insert_post($post);
            echo $count . PHP_EOL;
            $count++;
	}
        fclose($csv);
    }
}

function update_wp_db($wp_conn) {
	// update wp url stuff
	// get connection
	echo 'update_wp_db()';
       
        $wp_db = mysql_select_db($creds['wdb'],$wp_conn);
 
	// report success 
	if (!$wp_conn) {
	    die ('could not connect: ' . mysql_error());
	} 	
	echo 'Connected successfully!';

	$sql = mysql_query("update iug_options set option_value = 'http://headlineclub.org' where option_name in ('siteurl');");
	mysql_close($wp_conn);
}

?>
