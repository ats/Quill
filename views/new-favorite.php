  <div class="narrow">
    <?= partial('partials/header') ?>

      <div style="clear: both;" class="notice-pad">
        <div class="alert alert-success hidden" id="test_success"><strong>Success!</strong><br>Your post should be on your website now!<br><a href="" id="post_href">View your post</a></div>
        <div class="alert alert-danger hidden" id="test_error"><strong>Your endpoint did not return a Location header.</strong><br>See <a href="/creating-a-micropub-endpoint">Creating a Micropub Endpoint</a> for more information.</div>
      </div>

      <form role="form" style="margin-top: 20px;" id="note_form">

        <div class="form-group">
          <label for="like_of">URL to Favorite (<code>like-of</code>)</label>
          <input type="text" id="like_of" value="<?= $this->like_of ?>" class="form-control">
        </div>

<!-- added 10 March 2018 -->
        <div class="form-group hidden" id="note-name">
          <label for="note_name">Issue Title</label>
          <input type="text" id="note_name" value="" class="form-control" placeholder="">
        </div>

        <div class="form-group">
          <div id="note_content_remaining" class="pcheck206"><img src="/images/twitter.ico"> <span>280</span></div>
          <label for="note_content">Content</label>
          <textarea id="note_content" value="" class="form-control" style="height: 4em;"></textarea>
        </div>

        <div class="form-group hidden" id="content-type-selection">
          <label for="note_content_type">Content Type</label>
          <select class="form-control" id="note_content_type">
            <option value="text/plain">Text</option>
            <option value="text/markdown">Markdown</option>
          </select>
        </div>

<!--        <div class="form-group" id="form_tags">
          <label for="note_category">Tags</label>
          <input type="text" id="note_category" value="" class="form-control" placeholder="e.g. web, personal"> 
        </div> -->
<!-- end add -->

        <div style="float: right; margin-top: 6px;">
          <button class="btn btn-success" id="btn_post"><?= $this->url ? 'Save' : 'Post' ?></button>
        </div>

        <input type="hidden" id="edit_url" value="<?= $this->url ?>">
      </form>

      <div style="clear: both;"></div>

      <hr>
      <div style="text-align: right;" id="bookmarklet">
        Bookmarklet: <a href="javascript:<?= js_bookmarklet('partials/favorite-bookmarklet', $this) ?>" class="btn btn-default btn-xs">favorite</a>
      </div>

  </div>
<script>
$(function(){

  $("#btn_post").click(function(){
    $("#btn_post").addClass("loading disabled");

    var syndications = [];
    $("#syndication-container button.btn-info").each(function(i,btn){
      syndications.push($(btn).data('syndication'));
    });

    $.post("/favorite", {
      like_of: $("#like_of").val(),
      content: $("#note_content").val(),
      edit: $("#edit_url").val()
    }, function(response){
      if(response.location != false) {

        $("#test_success").removeClass('hidden');
        $("#test_error").addClass('hidden');
        $("#post_href").attr("href", response.location);

        window.location = response.location;
      } else {
        $("#test_success").addClass('hidden');
        $("#test_error").removeClass('hidden');
        if(response.error_details) {
          $("#test_error").text(response.error_details);
        }
        $("#btn_post").removeClass("loading disabled");
      }

    });
    return false;
  });

  <? if($this->autosubmit): ?>
    $(".footer, #bookmarklet").hide();
    $("#btn_post").click();
  <? endif ?>

  bind_syndication_buttons();
});

<?= partial('partials/syndication-js') ?>

</script>
