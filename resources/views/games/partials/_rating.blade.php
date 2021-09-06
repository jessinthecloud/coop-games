<script>

        let progressBarContainer{{ $count }} = document.getElementById('{{ $slug }}');

        let bar{{ $count }} = new ProgressBar.Circle(progressBarContainer{{ $count }}, {
            color: 'white',
            // This has to be the same size as the maximum width to
            // prevent clipping
            strokeWidth: 6,
            trailWidth: 3,
            easing: 'easeInOut',
            duration: 2000,
            text: {
                autoStyleContainer: false
            },

            trailColor: '#150530',
            from: { color: '#6a1fea', width: 6 },
            to: { color: '#6a1fea', width: 6 },

            // Set default step function for all animate calls
            step: function(state, circle) {
                circle.path.setAttribute('stroke', state.color);
                circle.path.setAttribute('stroke-width', state.width);

                let value = Math.round(circle.value() * 100);
                if (value === 0) {
                    circle.setText('N/A');
                } else {
                    circle.setText(value+'%');
                }
            }
        });

        bar{{ $count }}.animate({{ $rating }} / 100);  // Number from 0.0 to 1.0
</script>