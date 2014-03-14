$(function(){



        url_data = $('#graph').data('url');

        $.getJSON(url_data, $('.form-inline').serialize() , function(data){

            var
                container = document.getElementById('graph'),
                container2 = document.getElementById('graph-by-size'),
                horizontal = false,
                counter = data['count'],
                large = data['size'],
                b1 = counter[0], b2 = counter[1],b3 = counter[2],b4 = counter[3], b5 = counter[4], b6 = counter[5],
                l1 = large[0], l2 = large[1],l3 = large[2],l4 = large[3], l5 = large[4], l6 = large[5];


            // alert(data[1])
            (function() {
                // Draw the graph
               graph = Flotr.draw(container, [
               {
                    data: b1,
                    label: data['titles'][0]
                }, {
                    data: b2,
                    label: data['titles'][1]
                }, {
                    data: b3,
                    label: data['titles'][2]
                }, {
                    data: b4,
                    label: data['titles'][3]
                }, {              
                    data: b5,
                    label: data['titles'][4]
                }, {
                    data: b6,
                    label: data['titles'][5]
                }], {
                    xaxis: {
                        noTicks: 5,
                        tickFormatter: function(x) {
                            var
                                x = parseInt(x),
                                libraries = [ 'AGK', 'HBZ', 'UBB', 'ZBZ', 'ZHB' ];
                            return libraries[x-1];
                        }
                    },                    
                    legend: {
                        backgroundColor: '#D2E8FF' // Light blue 
                    },
                    bars: {
                        show: true,
                        stacked: true,
                        horizontal: horizontal,
                        barWidth: 0.3,
                        lineWidth: 1,
                        shadowSize: 0
                    },
                    grid: {
                        verticalLines: horizontal,
                        horizontalLines: !horizontal
                    },
                    mouse: {
                        track: true,
                        relative: true
                    },                    
                    spreadsheet: {
                        show: true,
                     tickFormatter: function(x) {
                            var
                                x = parseInt(x),
                                libraries = [ 'AGK', 'HBZ', 'UBB', 'ZBZ', 'ZHB' ];
                            return libraries[x-1];
                        }
                    }                    
                });
            })();

            (function() {
                // Draw the graph
               graph = Flotr.draw(container2, [
               {
                    data: l1,
                    label: data['titles'][0]
                }, {
                    data: l2,
                    label: data['titles'][1]
                }, {
                    data: l3,
                    label: data['titles'][2]
                }, {
                    data: l4,
                    label: data['titles'][3]
                }, {              
                    data: l5,
                    label: data['titles'][4]
                }, {
                    data: l6,
                    label: data['titles'][5]
                }], {
                    xaxis: {
                      noTicks: 5,
                      tickFormatter: function(x) {
                          var
                              x = parseInt(x),
                              libraries = [ 'AGK', 'HBZ', 'UBB', 'ZBZ', 'ZHB' ];
                          return libraries[x-1];
                      }
                    },                    
                    legend: {
                        backgroundColor: '#D2E8FF' // Light blue 
                    },
                    bars: {
                        show: true,
                        stacked: true,
                        horizontal: horizontal,
                        barWidth: 0.3,
                        lineWidth: 1,
                        shadowSize: 0
                    },
                    grid: {
                        verticalLines: horizontal,
                        horizontalLines: !horizontal
                    },
                    mouse: {
                        track: true,
                        relative: true
                    },                    
                    spreadsheet: {
                        show: true,
                     tickFormatter: function(x) {
                            var
                                x = parseInt(x),
                                libraries = [ 'AGK', 'HBZ', 'UBB', 'ZBZ', 'ZHB' ];
                            return libraries[x-1];
                        }
                    }                    
                });
            })();

        })

    

})
