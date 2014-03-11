$(function(){



        url_data = $('#graph').data('url');

        $.getJSON(url_data, function(data){

            var
                container = document.getElementById('graph'),
                horizontal = false,
                b1 = data[0], b2 = data[1],b3 = data[2],b4 = data[3], b5 = data[4], b6 = data[5];

            // alert(data[1])
            (function() {
                // Draw the graph
               graph = Flotr.draw(container, [
               {
                    data: b1,
                    label: 'Confirmeds'
                }, {
                    data: b2,
                    label: 'Sents'
                }, {
                    data: b3,
                    label: 'Integrateds'
                }, {
                    data: b4,
                    label: 'Reviseds'
                }, {              
                    data: b5,
                    label: 'Trasheds'
                }, {
                    data: b6,
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
