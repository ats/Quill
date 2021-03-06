  <div class="narrow">
    <?= partial('partials/header') ?>

      <div style="float: right; margin-top: 6px;">
        <button class="btn btn-success" id="btn_post">Save Bookmark</button>
      </div>

      <div style="clear: both;">
        <div class="alert alert-success hidden" id="test_success"><strong>Success! We found a Location header in the response!</strong><br>Your post should be on your website now!<br><a href="" id="post_href">View your post</a></div>
        <div class="alert alert-danger hidden" id="test_error"><strong>Your endpoint did not return a Location header.</strong><br>See <a href="/creating-a-micropub-endpoint">Creating a Micropub Endpoint</a> for more information.</div>
      </div>

      <form role="form" style="margin-top: 20px;" id="note_form">

        <div class="form-group">
          <label for="note_bookmark">Bookmark URL</label>
          <input type="text" id="note_bookmark" value="<?= $this->bookmark_url ?>" class="form-control">
        </div>

        <div class="form-group">
          <label for="note_name">Name</label>
          <input type="text" id="note_name" value="<?= $this->bookmark_name ?>" class="form-control">
        </div>

        <div class="form-group">
          <label for="note_content">Content</label>
          <textarea id="note_content" value="" class="form-control" style="height: 5em;"><?= $this->bookmark_content ?></textarea>
        </div>

        <div class="form-group">
          <label for="note_category">Tags</label>
          <input type="text" id="note_category" value="<?= $this->bookmark_tags ?>" class="form-control" placeholder="e.g. web, personal">
        </div>

        <div class="form-group">
          <label for="note_syndicate-to">Syndicate <a href="javascript:reload_syndications()">refresh</a></label>
          <div id="syndication-container">
            <?php
            if($this->syndication_targets) {
              echo '<ul>';
              foreach($this->syndication_targets as $syn) {
                echo '<li>'
                 . '<button data-syndicate-to="'.(isset($syn['uid']) ? htmlspecialchars($syn['uid']) : htmlspecialchars($syn['target'])).'" class="btn btn-default btn-block">'
                   . ($syn['favicon'] ? '<img src="'.htmlspecialchars($syn['favicon']).'" width="16" height="16"> ' : '')
                   . htmlspecialchars($syn['target'])
                 . '</button>'
               . '</li>';
              }
              echo '</ul>';
            } else {
              ?><div class="bs-callout bs-callout-warning">No syndication targets were found on your site.
              You can provide a <a href="/docs#syndication">list of supported syndication targets</a> that will appear as checkboxes here.</div><?php
            }
            ?>
          </div>
        </div>
      </form>


      <hr>
      <div style="text-align: right;">
        Bookmarklet: <a href="javascript:<?= js_bookmarklet('partials/bookmark-bookmarklet', $this) ?>" class="btn btn-default btn-xs">bookmark</a>
      </div>

  </div>

<script>
$(function(){

  $("#note_category").tokenfield({
    createTokensOnBlur: true,
    beautify: true
  });  

  $("#btn_post").click(function(){

    if($("#note_bookmark").val() == "") {
      return false;
    }

    var syndications = [];
    $("#syndication-container button.btn-info").each(function(i,btn){
      syndications.push($(btn).data('syndicate-to'));
    });

    $("#btn_post").addClass("loading disabled").text("Working...");

    $.post("/micropub/post", {
      'bookmark-of': $("#note_bookmark").val(),
      name: $("#note_name").val(),
      content: $("#note_content").val(),
      category: csv_to_array($("#note_category").val()),
      '<?= $this->user->micropub_syndicate_field ?>': syndications
    }, function(response){
      if(response.location != false) {

        $("#test_success").removeClass('hidden');
        $("#test_error").addClass('hidden');
        $("#post_href").attr("href", response.location);
        $("#note_form").addClass('hidden');

        // $("#note_bookmark").val("");
        // $("#note_content").val("");
        // $("#note_category").val("");

        window.location = response.location;
      } else {
        $("#test_success").addClass('hidden');
        $("#test_error").removeClass('hidden');
        $("#btn_post").removeClass("loading disabled").text("Post");
      }

    });
    return false;
  });

  bind_syndication_buttons();
});

<?= partial('partials/syndication-js') ?>

</script>
