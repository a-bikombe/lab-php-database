let id_count = 0;

document.getElementById("add-row").addEventListener("click",function() {
	const tr = document.getElementById("tr");
	const tbody = document.querySelector("tbody");
	const clone = tr.content.cloneNode(true);
	clone.querySelector("input").name = id_count + '-new-name';
	clone.querySelector("textarea").name = id_count + '-new-message';
	tbody.appendChild(clone);
	id_count++;
});