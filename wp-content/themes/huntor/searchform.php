<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="input-group">
        <label for="<?php echo esc_attr( $unique_id ); ?>">
            <span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'huntor' ); ?></span>
        </label>
        <input type="search" id="<?php echo esc_attr( $unique_id ); ?>" class="search-field form-control"
               placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'huntor' ); ?>"
               value="<?php echo get_search_query(); ?>" name="s"/>
        <span class="input-group-btn">
            <button type="submit" class="search-submit">
                <span class="opal-icon-search"></span>
                <span class="screen-reader-text"><?php echo esc_html_x( 'Search', 'submit button', 'huntor' ); ?></span>
            </button>
        </span>
    </div>
</form>


