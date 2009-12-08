/* 
 * This function is used to change the multiple selection of checkboxes
 * to a comma-separated list of uid's
 *
 * @param	parentElementID	ID of the element that encompasses all the relevant checkboxes
 * @param	hiddenFieldID	ID of the hidden field to update
 *
 * $Id$
 */
function updateSelectedList(parentElementID, hiddenFieldID) {
	var selectionString = '';
	$$("#" + parentElementID + " input[type='checkbox']").each(function(element) {
		if (element.checked) {
			if (selectionString != '') {
				selectionString += ',';
			}
			selectionString += element.value;
		}
	});
	$(hiddenFieldID).value = selectionString;
}