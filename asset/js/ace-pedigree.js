$(function() {
    $.fn.kinshipGraph = function(dataUrl) {
        var Graph = ForceGraph3D()
            (this[0])
            .width($(this).width())
            .height($(this).width() * 0.6)
            .backgroundColor('rgba(0, 0, 0, 0)')
            .enableNodeDrag(false)
            .nodeResolution(16)
            .nodeOpacity(1)
            .nodeVal(node => node.value * 10)
            .linkVisibility(false)
            .jsonUrl(dataUrl);

        Graph.d3Force('link').distance(link => link.distance * 100);

        var tooltipObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length == 1 && mutation.addedNodes[0].nodeType == 3) {
                    $(mutation.target).html('<div class="tooltip-inner">' + $(mutation.target).text() + '</div>');
                }
            });
        });

        tooltipObserver.observe(
            $('.scene-tooltip', this).removeClass('scene-tooltip').addClass('tooltip show')[0],
            { childList: true }
        );

        let resizeGraph;
        (resizeGraph = function() {
            $(Graph.renderer().domElement)
                .css('width', $(this).width() + 'px')
                .css('height', ($(this).width() * 0.6) + 'px');
        })();

        $(window).on('resize', resizeGraph);

        let changeNodeColor;
        (changeNodeColor = function() {
            Graph.nodeColor(node => $('body').css('--primary'));
        })();

        $(window).on('changestyles', changeNodeColor);
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