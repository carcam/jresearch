changeValue = function(name)
{
	var selBox = $('projectslist');
	var txtBox = $(name);
	var value = selBox.options[selBox.selectedIndex].value;
	txtBox.value = "";
		
	for(var i=0;i<selBox.options.length;i++)
	{
		if(selBox.options[i].selected === true)
		{
			if(txtBox.value == "")
			{
				txtBox.value = selBox.options[i].value;
			}
			else
			{
				txtBox.value += ","+selBox.options[i].value;
			}
		}
	}
};

selectAll = function(name)
{
	var selBox = $('projectslist');
	
	for(var i=0; i<selBox.options.length;i++)
	{
		selBox.options[i].selected = true;
	}
};

unselectAll = function(name)
{
	var selBox = $('projectslist');
	$(name).value = '0';
	
	
	for(var i=0; i<selBox.options.length;i++)
	{
		if(selBox.options[i].selected === true)
		{
			selBox.options[i].selected = false;
		}
	}
};