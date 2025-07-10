$(document).ready(function() {
    var working = false;
    $('.login').on('submit', function(e) {
        if (working) {
            e.preventDefault();
            return;
        }
        working = true;
        var $this = $(this),
            $state = $this.find('button > .state');
        $this.addClass('loading');
        $state.html('Authenticating');
    });
});