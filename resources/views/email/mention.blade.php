<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>

    <?php

    ?>
    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <center>
            <table border="0">
              <tr>
                <td align="center" valign="top">
                  Hi! You have been mentioned in room:
                </td>
              </tr>
              <tr>
                <td align="center" valign="top">
                  <h2>{{ $group_name }}</h2>
                </td>
              </tr>
              <tr>
                <td align="center" valign="top">
                  <p>Message Preview:</p>
                </td>
              </tr>
              <tr>
                <td align="center" valign="top">
                  {!! $content !!}
                </td>
              </tr>
            </table>
        </center>
      </body>
</html>