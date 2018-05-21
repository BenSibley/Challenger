jQuery(document).ready(function($){

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
});