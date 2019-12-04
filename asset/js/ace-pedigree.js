$(function() {
    $.fn.kinshipGraph = function(dataUrl) {
        var Graph = ForceGraph3D()
            (this[0])
            .width($(this).width())
            .height($(this).width() * 0.6)
            .backgroundColor('#ffffff')
            .enableNodeDrag(false)
            .nodeResolution(16)
            .nodeColor(node => 'hsl(210, 65%, 50%)')
            .nodeOpacity(1)
            .nodeVal(node => node.value * 10)
            .linkVisibility(false)
            .jsonUrl(dataUrl);

        Graph.d3Force('link').distance(link => link.distance * 100);

        let resizeGraph;
        (resizeGraph = function() {
            $(Graph.renderer().domElement)
                .css('width', $(this).width() + 'px')
                .css('height', ($(this).width() * 0.6) + 'px');
        })();

        $(window).on('resize', resizeGraph);
    };

    $('#addAnimal').on('show.bs.modal', function(e) {
        var sex = $(e.relatedTarget).attr('data-sex');
        $('.selectpicker', this)
            .html('<option value="">Search...</option>').selectpicker('refresh')
            .attr('data-hidden-name', (sex == 1 ? 'sire' : 'dam'))
            .attr('data-qsa', 'sex=' + sex);
    });

    $('#addAnimal form').prettyForm().on('submit', function(e) {
        var name = $('.selectpicker', this).attr('data-hidden-name');
        $('[name="' + name + '"]', this).val($('.selectpicker', this).val());
        $('select, input[value=""], button').removeAttr('name');
    });
});