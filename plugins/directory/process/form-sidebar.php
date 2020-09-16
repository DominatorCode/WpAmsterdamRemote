<div class="rates_box">

    <style scoped>
        .rates_box {
            margin-bottom: 30px;
        }
        .meta_options h4 {
            margin-top: 30px !important;
        }
        ul.rate-option li {
            display: block;
            margin-right: 10px;
        }
        .rate-option input[type="number"] {
            text-align: right;
            font-size: 1.25em;
        }
        ul.rate-option input {
            float: right;
        }

    </style>

    <!-- Rates -->
    
    <div class="meta_options">
        <h4>Additional Hour:</h4>
        <ul class="rate-option">
        <li>
        <label for="add_hour_in">IN:</label>
        <input 
            id="add_hour_in" 
            type="number"
            name="add_hour_in"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'add_hour_in', true) ); ?>"
        >
        </li>
        <li>
        <label for="add_hour_out">OUT:</label>
        <input 
            id="add_hour_out" 
            type="number"
            name="add_hour_out"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'add_hour_out', true) ); ?>"
        >
        </li>
        </ul>
    </div>
    
   
    <div class="meta_options">
        <h4>1 Hour:</h4>
        <ul class="rate-option">
        <li>
        <label for="one_hour_in">IN:</label>
        <input 
            id="one_hour_in" 
            type="number"
            name="one_hour_in"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'one_hour_in', true) ); ?>"
        >
        </li>
        <li>
        <label for="one_hour_out">OUT:</label>
        <input 
            id="one_hour_out" 
            type="number"
            name="one_hour_out"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'one_hour_out', true) ); ?>"
        >
        </li>
        </ul>
    </div>
    
    
    <div class="meta_options">
        <h4>Dinner Date:</h4>
        <ul class="rate-option">
        <li>
        <label for="dinner_date_in">IN:</label>
        <input 
            id="dinner_date_in" 
            type="number"
            name="dinner_date_in"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'dinner_date_in', true) ); ?>"
        >
        </li>
        <li>
        <label for="dinner_date_out">OUT:</label>
        <input 
            id="dinner_date_out" 
            type="number"
            name="dinner_date_out"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'dinner_date_out', true) ); ?>"
        >
        </li>
        </ul>
    </div>

   
    <div class="meta_options">
        <h4>Overnight:</h4>
        <ul class="rate-option">
        <li>
        <label for="overnight_in">IN:</label>
        <input 
            id="overnight_in" 
            type="number"
            name="overnight_in"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'overnight_in', true) ); ?>"
        >
        </li>
        <li>
        <label for="overnight_out">OUT:</label>
        <input 
            id="overnight_out" 
            type="number"
            name="overnight_out"
            value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'overnight_out', true) ); ?>"
        >
        </li>
        </ul>
    </div>
    

</div>