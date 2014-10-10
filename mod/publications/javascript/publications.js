        /**
         * @package Elgg
         * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
         * @author Roger Curry, Grid Research Centre [curry@cpsc.ucalgary.ca]
         * @author Tingxi Tan, Grid Research Centre [txtan@cpsc.ucalgary.ca]
         * @link http://grc.ucalgary.ca/
         */


function add(){
	var src = document.getElementById('authorinput');
	var dest = document.getElementById('authorselected');
	var index=src.length-1;
	while(index > -1){
		if(src.options[index].selected){
	 	username = src.options[index].text;
		guid = src.options[index].value;
		dest.options[dest.options.length] = new Option(username,guid,false,false);
		src.remove(index);
		}
		index--;
	}	
}

function remove(){
	var src = document.getElementById('authorselected');
        var dest = document.getElementById('authorinput');
        var index=src.length-1;
        while(index > -1){
                if(src.options[index].selected){
		username = src.options[index].text;
                guid = src.options[index].value;
                dest.options[dest.options.length] = new Option(username,guid,false,false);
               	src.remove(index);
		}
                index--;
        }
}

function addunregisteredauthor(){
	var src=document.getElementById('unregistered');
	var dest=document.getElementById('authorselected');
	var username = src.value;
	var guid='new,'+username;
	dest.options[dest.options.length] = new Option(username,guid,false,false);
	src.value = '';
}

function selectall(){
	var src = document.getElementById('authorselected');
	var index=src.length-1;
	while(index > -1){
		src.options[index].selected = 'selected';
		index--;
	}
}
function up(){
	var src=document.getElementById('authorselected');
	var index=src.length-1;
	while(index > -1){
		if(src.options[index].selected){
			finalindex = index-1;
			if(index != 0){
			t = new Option(src.options[index-1].text,src.options[index-1].value,false,false);
			c = new Option(src.options[index].text,src.options[index].value,false,false);
			src.options[index-1] = c;
			src.options[index] = t;
			}
		}
		index--;
	}
	if(finalindex < 0) finalindex = 0;
	src.options[finalindex].selected = 'selected';
}
function down(){
	var src=document.getElementById('authorselected');
        var index=src.length-1;
        while(index > -1){
                if(src.options[index].selected){
                        finalindex = index+1;
                        if(index != src.length-1){   
 	                t = new Option(src.options[index+1].text,src.options[index+1].value,false,false);                        
			c = new Option(src.options[index].text,src.options[index].value,false,false);
                        src.options[index+1] = c;
                        src.options[index] = t;
                        }
                }
                index--;
        }
        if(finalindex > src.length-1) finalindex = src.length-1;
        src.options[finalindex].selected = 'selected';
}

function addattachment(fileguid,name){
	$("#attachment_name").val(name);
	$("#attachment_guid").val(fileguid);
	$.facebox.close();
}
	
