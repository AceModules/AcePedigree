function kinshipGraph($container, nodes, links) {
    var Graph = ForceGraph3D()
        ($container[0])
        .width($container.width())
        .height($container.width() * 0.6)
        .backgroundColor('#ffffff')
        .enableNodeDrag(false)
        .nodeResolution(16)
        .nodeColor(node => 'hsl(210, 65%, 50%)')
        .nodeOpacity(1)
        .nodeVal(node => node.value * 10)
        .linkVisibility(false)
        .graphData({
            nodes: nodes,
            links: links
        });

    Graph.d3Force('link').distance(link => link.distance * 1000);

    let resizeGraph;
    (resizeGraph = function() {
        $(Graph.renderer().domElement)
            .css('width', $container.width() + 'px')
            .css('height', ($container.width() * 0.6) + 'px');
    })();

    $(window).on('resize', resizeGraph);
}