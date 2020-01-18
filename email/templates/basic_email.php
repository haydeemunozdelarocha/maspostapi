<?php
echo '<style type="text/css">
    .header {
        background: #8a8a8a;
    }
    .header .columns {
        padding-bottom: 0;
    }
    .header p {
        color: #fff;
        margin-bottom: 0;
    }
    .header .wrapper-inner {
        padding: 20px; /*controls the height of the header*/
    }
    .header .container {
        background: #8a8a8a;
    }
    .wrapper.secondary {
        background: #f3f3f3;
    }
</style>
<!-- move the above styles into your custom stylesheet -->

<wrapper class="header">
    <container>
        <row class="collapse">
            <columns small="6">
                <img src="http://placehold.it/200x50/663399">
            </columns>
            <columns small="6">
                <p class="text-right">HERO</p>
            </columns>
        </row>
    </container>
</wrapper>

<container>

    <spacer size="16"></spacer>

    <row>
        <columns small="12">
            <h1>Hi, Elijah Baily</h1>
            <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi impedit sapiente delectus molestias quia.</p>
            <img src="http://placehold.it/548x300" alt="">
            <callout class="primary">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veniam assumenda, praesentium qui vitae voluptate dolores. <a href="#">Click it!</a></p>
            </callout>
            <h2>Title Ipsum <small>This is a note.</small></h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi repellat, harum. Quas nobis id aut, aspernatur, sequi tempora laborum corporis cum debitis, ullam, dolorem dolore quisquam aperiam! Accusantium, ullam, nesciunt. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus consequuntur commodi, aut sed, quas quam optio accusantium recusandae nesciunt, architecto veritatis. Voluptatibus sunt esse dolor ipsum voluptates, assumenda quisquam.</p>

            <button class="large secondary" href="#">Click Me!</button>

        </columns>
    </row>

    <wrapper class="secondary">
        <spacer size="16"></spacer>
        <row>
            <columns small="12" large="6">
                <h5>Connect With Us:</h5>
                <menu class="vertical" align="left">
                    <item style="text-align: left;" href="#">Twitter</item>
                    <item style="text-align: left;" href="#">Facebook</item>
                    <item style="text-align: left;" href="#">Google +</item>
                </menu>
            </columns>
            <columns small="12" large="6">
                <h5>Contact Info:</h5>
                <p>Phone: 408-341-0600</p>
                <p>Email: <a href="mailto:foundation@zurb.com">foundation@zurb.com</a></p>
            </columns>
        </row>
    </wrapper>

    <center>
        <menu>
            <item href="#">Terms</item>
            <item href="#">Privacy</item>
            <item href="#">Unsubscribe</item>
        </menu>
    </center>

</container>';
