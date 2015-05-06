<?php get_header(); ?>
<div id="main">
   <!--Main Content-->
   <div id="content"  class="container_24">
      <div class="grid_16">
         <div class="news" id="news">
            <ul class="sub-cat-tabs">
               <li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="#all-tab">All Articles</a></li>
            </ul>
            <div class="content-container fullView">
               <section id="tab-all">
                  <header>
                     <a class="rss left" href="<?php bloginfo('rss_url'); ?>"><span class="rss-icon"></span>View the RSS Feed</a>
                     <ul class="sort right">
                        <li><a class="full-view active" href="#" title="Full View"><span>full</span></a></li>
                        <li><a class="list-view" href="#" title="List View"><span>list</span></a></li>
                     </ul>
                     <div class="clear"></div>
                  </header>
                  <?php if (have_posts()) : while(have_posts()) : the_post();
                     get_template_part('content', get_post_format());
                     endwhile; else : ?>
                  <article id="post-<?php the_ID(); ?>" <?php post_class('no-posts'); ?>>
                     <h1><?php _e('No posts were found.', 'mediaedge-framework'); ?></h1>
                  </article>
                  <?php endif;
                     if ($wp_query->post_count < $posts_per_page) { ?>
                  <button id="index-more" class="show_more disabled">No More Posts</button>
                  <?php } else { ?>
                  <button id="index-more" class="show_more disabled">Show More</button>
                  <?php } ?>
               </section>
            </div>
         </div>
      </div>
      <?php get_sidebar( 'right' ); ?>
   </div>
</div>
<script type="text/javascript">
   jQuery(function($){
       var page = 2;
       $('#index-more').click(function() {
           var loading = true,
               $window = $(window),
               current = $(this),
               load_posts = function(){
                   $.ajax({
                       type       : 'GET',
                       data       : {numPosts : 6, pageNumber: page},
                       dataType   : "html",
                       url        : "<?php echo get_template_directory_uri(); ?>/loopHandler.php",
                       success    : function(data){
                           $data = $(data);
                           console.log(page);
                           if ($data.length < <?php echo (isset($mediaedge_settings['news-show_more']['show'])) ? $mediaedge_settings['news-show_more']['show'] : $posts_per_page; ?>) {
                               current.addClass('disabled').text('No More Posts').attr('disabled', 'disabled');
                           }
                           if ($data.length){
                               $data.hide();
                               current.before($data);
                               $data.fadeIn(500, function() {
                                   $("#temp_load").remove();
                                   loading = false;
                               });
                               page++;
                           } else {
                               $("#temp_load").remove();
                           }
                       },
                       error: function(jqXHR, textStatus, errorThrown) {
                           current.text('An error has occured, please try again.');
                       }
                   });
               }
           load_posts();
       });
   });
</script>
<?php get_footer(); ?>
