<?php
/**
 * The GSR Salary Calulator. Sets a new 30-day cookie session if one does not already exist. Computations are done and
 * returned by ./src/javascript.php.
 *
 * @author Brandon Clement
 * @since 29 October 2015
 */

include "/var/www/html/php-programs/globalLib.php";

// "GSR Salary Calculator"
list($smartHeader, $smartFooter) = getSetHeaderFooter("/human-resources/hire-a-student/gsrs/gsr-salary-calculator/", "", 1);
print $smartHeader;

/**
 * Generates a 32-character key for the cookie session
 *
 * @author Brandon Clement
 * @since 29 October 2015
 * @return string 32-character key
 */
function generateKey() {
  $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  $prefix = "";
  
  for ($i = 0; $i < 19; $i++) {
    $prefix .= $chars[rand(0, strlen($chars) - 1)];
  }
  
  return uniqid($prefix);
}    

if (!array_key_exists("gsr_session_id", $_COOKIE)) {
  setcookie("gsr_session_id", generateKey(), time() + (86400 * 30), "/"); // 86400 = 1 day; Expires after 30 days
}
?>

<link type="text/css" rel="stylesheet" href="src/style.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="src/javascript.php"></script>

<h3 class="subtitle">Effective date <span id="effective"></span></h3>

<noscript class="notice">Your browser does not have JavaScript. This calculator will not function without it.</noscript>

<details>
  <strong>Directions:</strong>
  <br />This caluclator is designed to assist you with calculating the details of your student's appointment. You may enter the estimated monthly pay, hours, or time for the appointment. Please note the following:
  <ul>
    <li>Percentages must be whole numbers</li>
    <li>Monthly hours and pay entered will be rounded down to the next lowest standard bracket on the salary chart. (For example, if you enter an estimated monthly pay of $600, the calculator will return the next closest standard value from the salary chart. In this case, it would the values associated with an estimated monthly pay of $577.79.)</li>
  </ul>
</details>

<form id="calculator" method="post">
  <table>
    <tr>
      <td>Calculate based on</td>
      <td>
        <select name="method" id="method">
          <option value="pay">Monthly Pay</option>
          <option value="hours">Monthly Hours</option>
          <option value="percent">Percent Time</option>
        </select>
      </td>
      <td>of</td>
      <td>
        <input type="input" name="data" id="data" placeholder="Numerical Value" required="required" pattern="[0-9]+\.?[0-9]*|[0-9]*\.?[0-9]+" title="a number without symbols (decimal points excepted)" />
      </td>
    </tr>
    <tr>
      <td colspan="4">
        <input type="submit" value="Calculate" />
        <input type="reset" id="reset" value="Reset" />
      </td>
    </tr>
  </table>
</form>

<table>
  <thead>
    <tr>
      <th>Percent Time</th>
      <th>Estimated Monthly Hours</th>
      <th>Estimated Monthly Pay</th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <td colspan="3" id="remissionCell">No Fee Remission</td>
    </tr>
  </tfoot>
  <tbody>
    <tr>
      <td id="percentCell">0</td>
      <td id="hoursCell">0.00</td>
      <td id="payCell">0.00</td>
    </tr>
  </tbody>
</table>

<details id="roundInfo" class="notice">
  The table displays your input rounded down to the next lowest standard bracket on the salary chart.
</details>

<?php print $smartFooter; ?>
