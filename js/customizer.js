jQuery(document).ready(function($){

  /* === Toggle visibility of post comments link control === */
  const fullPostControl = $('#customize-control-full_post');
  toggleCommentLink();
  fullPostControl.on('click', toggleCommentLink);
  
  // Show/hide comment link when showing full posts
  function toggleCommentLink() {
    if ( fullPostControl ) {
      if ( fullPostControl.find('input:checked').val() == 'yes' ) {
        $('#customize-control-comment_link').addClass('show');
      } else {
        $('#customize-control-comment_link').removeClass('show');
      }
    }
  }

  /* === Header box display options === */

  jQuery( '.customize-control-checkbox-multiple input[type="checkbox"]' ).on( 'change', function() {
    checkbox_values = jQuery( this ).parents( '.customize-control' ).find( 'input[type="checkbox"]:checked' ).map(
      function() {
        return this.value;
      }
    ).get().join( ',' );
    jQuery( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
  }
);
});