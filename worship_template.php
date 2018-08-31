<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Central Baptist Church Weekly Update - <?php echo $start_date; ?></title>
        <link href="https://fonts.googleapis.com/css?family=Frank+Ruhl+Libre:400,700|Lato:400,700" rel="stylesheet"> 
        <style type="text/css">
            body {
                font-size: 10px;
                font-family: 'Frank Ruhl Libre', serif;
            }
            h1 {
                font-family: 'Lato', sans-serif;
                font-size: 10px;
                font-weight: bold;
            }
            h1.title {
                font-family: 'Frank Ruhl Libre', serif;
                font-size: 28px;
                font-weight: normal;
            }
            h2 {
                font-family: 'Frank Ruhl Libre', serif;
                font-size: 10px;
                font-weight: normal;
            }
            h3.subtitle {
                font-family: 'Lato', sans-serif;
                font-size: 9px;
                font-weight: 300;
                text-transform: uppercase;
            }
            .credit {
                font-size: 9px;
                font-style: italic;
                
            }
        </style>
	</head>

<?php 
	//get page data
	$audio_args = array(
		'numberposts'   => 3,
		'offset'           => 0,
		'orderby'          => 'date',
		'order'            => 'DESC',
		'post_type'        => 'post',
		'post_status'      => 'publish',
        'suppress_filters' => true ,
        'category_name' => 'audio'
	);
	$blog_args = array(
		'numberposts'   => 3,
		'offset'           => 0,
		'orderby'          => 'date',
		'order'            => 'DESC',
		'post_type'        => 'post',
		'post_status'      => 'publish',
        'suppress_filters' => true ,
        'category_name' => 'blog'
	);
	$audio_posts = get_posts($audio_args);
	$blog_posts = get_posts($blog_args);
    $worship_order = get_field('order');
    // echo '<pre>';
    // print_r( get_field('order')  );
    // echo '</pre>';
    // die;
?>
   <body>

                                            <h1 class="title"><?php echo get_field('worship_title'); ?></h1>
                                            <h3 class="subtitle"><?php echo strtoupper(get_field('date')); ?></h3>
                                        <?php foreach($worship_order as $item): ?>
                                                    <?php if($item['link'] && $item['element_link']->type == 'quote'): ?>
                                                        <hr>
                                                            <?php echo $item['element_link']->post_content; ?><br>
                                                            <?php echo '—'.$item['element_link']->author; ?>

                                                        <hr>
                                                    <?php else:?>
														<h1>
                                                            <?php echo $item['stand']? '+ ' : ''?><?php echo $item['heading'] ?>&Tab;<?php echo $item['leader'] ?>
                                                        </h1>
                                                        <?php if($item['link']): ?>
                                                                <h2>
                                                                    <?php if($item['element_title']): ?>
                                                                        <?php echo '"'.$item['element_link']->post_title.'"'; ?>
                                                                    <?php endif; ?>
                                                                    <?php if($item['element_link']->type == 'hymn' && $item['element_link']->hymn_number): ?>
                                                                        &Tab;<?php echo "#".$item['element_link']->hymn_number; ?>
                                                                    <?php elseif($item['element_link']->author !== ''): ?>
                                                                        &Tab;<?php echo $item['element_link']->author; ?>
                                                                    <?php endif; ?>
                                                                </h2>
                                                                <?php if($item['note']): ?>
                                                                    <?php echo $item['element_note'];?>
                                                                <?php endif; ?>
                                                                <?php if($item['text']):?>
                                                                    &Tab;<?php echo wpautop( $item['element_link']->post_content, true); ?>
                                                                <?php endif;?>
                                                                <?php if($item['element_link']->credit !== ''):?>
                                                                    &Tab;<span class="credit"><?php echo $item['element_link']->credit; ?></span>
                                                                <?php endif;?>
                                                        <?php else:?>  
                                                            <h2>
                                                                <?php if($item['element_title']): ?>
                                                                    <?php echo '"'.$item['title'].'"';?>
                                                                <?php endif; ?>
                                                                <?php if($item['text']): ?>
                                                                    <?php echo $item['italic'] ? '<em>' : '' ?>
                                                                    <?php echo $item['element_text'];?>
                                                                    <?php echo $item['italic'] ? '</em>' : '' ?>
                                                                <?php endif; ?>
                                                            </h2>
                                                        <?php endif; ?>
                                                     <?php endif; ?>   
    
													
                                    	<?php endforeach; ?>
                                       
    </body>
</html>