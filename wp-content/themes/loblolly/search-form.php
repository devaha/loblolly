<div class="search_main">
    <form method="get" class="searchform" action="<?php echo home_url( '/' ); ?>" >
        <input type="text" class="field s" name="s" value="<?php _e( 'SEARCH OUR SITE', 'woothemes' ); ?>" onfocus="if (this.value == '<?php _e( 'SEARCH OUR SITE', 'woothemes' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'SEARCH OUR SITE', 'woothemes' ); ?>';}" />
        <input type="image" src="<?php echo get_template_directory_uri(); ?>/images/ico-search.png" alt="<?php _e( 'Search', 'woothemes' ); ?>" class="submit" name="submit" />
    </form>    
    <div class="fix"></div>
</div>
