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
});