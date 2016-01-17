var fill = d3.scale.category20();

d3.json("?task=keywordsAndFrequency&format=json&controller=publications", function(data) {	
  
  d3.layout.cloud().size([100, 100])
      .words(data)
      .padding(5)
      .rotate(function() { return ~~(Math.random() * 2) * 90; })
      .font("Impact")
      .fontSize(function(d) { return Math.max(8, Math.min(d.size, 14)); })
      .on("end", draw)
      .start();

  function draw(words) {
    d3.select("#cloud").append("svg")
        .attr("width", 100)
        .attr("height", 100)
      .append("g")
        .attr("transform", "translate(50,50)")
      .selectAll("text")
        .data(data)
      .enter().append("text")
        .style("font-size", function(d) { return d.size + "px"; })
        .style("font-family", "Impact")
        .style("fill", function(d, i) { return fill(i); })
        .attr("text-anchor", "middle")
        .attr("transform", function(d) {
          return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
        })
        .text(function(d) { return d.text; });
  }
});
