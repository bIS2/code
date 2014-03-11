$(function(){



        url_data = $('#graph').data('url');

        $.getJSON(url_data, function(data){

            var
                container = document.getElementById('graph'),
                container2 = document.getElementById('graph-by-size'),
                horizontal = false,
                counter = data['counter'],
                large = data['large'],
                b1 = counter[0], b2 = counter[1],b3 = counter[2],b4 = counter[3], b5 = counter[4], b6 = counter[5],
                l1 = large[0], l2 = large[1],l3 = large[2],l4 = large[3], l5 = large[4], l6 = large[5];

            str_confirm 		= $('.confirm').text()
            str_sent 				= $('.sent').text()
            str_integrated 	= $('.integrated').text()
            str_revised 		= $('.revised').text()
            str_trashed 		= $('.trashed').text()
            str_deleted 		= $('.burned').text()

            // alert(data[1])
            (function() {
                // Draw the graph
               graph = Flotr.draw(container, [
               {
                    data: b1,
                    label: str_confirm
                }, {
                    data: b2,
                    label: str_sent
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
                    label: 'Confirmeds'
                }, {
                    data: l2,
                    label: 'Sents'
                }, {
                    data: l3,
                    label: 'Integrateds'
                }, {
                    data: l4,
                    label: 'Reviseds'
                }, {              
                    data: l5,
                    label: 'Trasheds'
                }, {
                    data: l6,
                    label: 'Eliminateds'
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
