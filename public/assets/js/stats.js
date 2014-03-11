$(function(){



        url_data = $('#graph').data('url');

        $.getJSON(url_data, function(data){
        	
            str_confirm 		= jQuery('.confirm').html()
            str_delivery 		= jQuery('.delivery').html()
            str_integrated 	= jQuery('.integrated').html()
            str_revised 		= jQuery('.revised').html()
            str_trashed 		= jQuery('.trashed').html()
            str_deleted 		= jQuery('.burned').html()

            var
                container = document.getElementById('graph'),
                container2 = document.getElementById('graph-by-size'),
                horizontal = false,
                counter = data['counter'],
                large = data['large'],
                b1 = counter[0], b2 = counter[1],b3 = counter[2],b4 = counter[3], b5 = counter[4], b6 = counter[5],
                l1 = large[0], l2 = large[1],l3 = large[2],l4 = large[3], l5 = large[4], l6 = large[5];


            // alert(data[1])
            (function() {
                // Draw the graph
               graph = Flotr.draw(container, [
               {
                    data: b1,
                    label: str_confirm
                }, {
                    data: b2,
                    label: str_delivery
                }, {
                    data: b3,
                    label: str_integrated
                }, {
                    data: b4,
                    label: str_revised
                }, {              
                    data: b5,
                    label: str_trashed
                }, {
                    data: b6,
                    label: str_deleted
                }], {
                    xaxis: {
                        noTicks: 5,
                        tickFormatter: function(x) {
                            var
                                x = parseInt(x),
                                libraries = ['ABKB','BSUB','LUZB','ZHUB','ZHZB'];
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
                                libraries = ['ABKB','BSUB','LUZB','ZHUB','ZHZB'];
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
                    label: str_confirm
                }, {
                    data: l2,
                    label: str_delivery
                }, {
                    data: l3,
                    label: str_integrated
                }, {
                    data: l4,
                    label: str_revised
                }, {              
                    data: l5,
                    label: str_trashed
                }, {
                    data: l6,
                    label: str_deleted
                }], {
                    xaxis: {
                      noTicks: 5,
                      tickFormatter: function(x) {
                          var
                              x = parseInt(x),
                              libraries = ['ABKB','BSUB','LUZB','ZHUB','ZHZB'];
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
                                libraries = ['ABKB','BSUB','LUZB','ZHUB','ZHZB'];
                            return libraries[x-1];
                        }
                    }                    
                });
            })();

        })

    

})
