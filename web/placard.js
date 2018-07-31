function showEMP(){
    document.getElementById("emp_placard").style.display = "block";
    document.getElementById("related-content").style.display = "none";
}
function showRS(){
    document.getElementById("related-content").style.display = "block";
    document.getElementById("emp_placard").style.display = "none";
}
function showEmpFtList(){
    document.getElementById("emp_hide_ft_list").style.display = "block";
    document.getElementById("emp_ft_list").style.display = "block";
    document.getElementById("emp_show_ft_list").style.display = "none";
}
function hideEmpFtList(){
    document.getElementById("emp_hide_ft_list").style.display = "none";
    document.getElementById("emp_ft_list").style.display = "none";
    document.getElementById("emp_show_ft_list").style.display = "block";
}