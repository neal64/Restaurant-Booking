<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" style="background: #FFF;">
        <div class="modal-header" style="border: 1px solid #e6e6e6">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Book Table </h4>
      </div>
      <div class="modal-body">
        <h5>Please Select Payment type</h5>
        <div class="">
            <table>
                <tr>
                    <td><input type="radio" name="pay" value="1" checked> </td>
                    <td> Debit Card</td>
                </tr>
                <tr>
                    <td><input type="radio" name="pay" value="2"> </td>
                    <td>credit Card</td>
                </tr>
                <tr>
                    <td><input type="radio" name="pay" value="3"> </td>
                    <td> Net Banking</td>
                </tr>
            </table>
        </div>
        
      </div>
      <div class="modal-footer"  style="border: 1px solid #e6e6e6">
          <button type="button" class="btn btn-success" data-dismiss="modal" onclick="save_now()">Book Now</button>
		  <p>cancle your booking 714-726-9450</p>
        <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>