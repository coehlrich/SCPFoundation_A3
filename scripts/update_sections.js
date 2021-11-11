const sections = document.getElementById("sections");

function addSection(pointer) {
    var index = parseInt(pointer.srcElement.id.replace("add-button-", ""));
    var elements = [];
    var readOnlyChildren = sections.children;

    var addFunction = (pointer) => addSection(pointer);
    var removeFunction = (pointer) => removeSection(pointer);
    
    var newSectionWrapper = document.createElement("div");
    newSectionWrapper.id = "section-" + index;

    var newSection = document.createElement("div");
    newSection.setAttribute("class", "bordered shadow p-3 section-form");

    var header = document.createElement("div");
    header.setAttribute("class", "row");
    
    var sectionTitle = document.createElement("div");
    sectionTitle.setAttribute("class", "col display-6 section-title");
    sectionTitle.innerText = "Section " + (index + 1);
    header.appendChild(sectionTitle);

    var removeButtonWrapper = document.createElement("div");
    removeButtonWrapper.setAttribute("class", "col text-end");

    var removeButton = document.createElement("button");
    removeButton.setAttribute("class", "btn btn-danger mb-3 remove-button");
    removeButton.type = "button";
    removeButton.onclick = removeFunction;
    removeButton.id = "remove-button-" + index;
    removeButton.innerText = "-";
    removeButtonWrapper.appendChild(removeButton);

    header.appendChild(removeButtonWrapper);

    newSection.appendChild(header);

    var titleLabel = document.createElement("label");
    titleLabel.innerText = "Section Title";
    newSection.appendChild(titleLabel);

    newSection.appendChild(document.createElement("br"));

    var title = document.createElement("input");
    title.type = "text";
    title.setAttribute("class", "form-control");
    title.name = "title-" + index;
    title.placeholder = "Type the title for the section";
    title.required = true;
    newSection.appendChild(title);
    
    newSection.appendChild(document.createElement("br"));

    var buttonLabel = document.createElement("label");
    buttonLabel.innerText = "Section Button";
    newSection.appendChild(buttonLabel);

    newSection.appendChild(document.createElement("br"));

    var button = document.createElement("input");
    button.type = "text";
    button.setAttribute("class", "form-control");
    button.name = "button-" + index;
    button.placeholder = "Type the text for the button for the section to show";
    button.required = true;
    newSection.appendChild(button);

    newSection.appendChild(document.createElement("br"));

    var contentLabel = document.createElement("label");
    contentLabel.innerText = "Section Content";
    newSection.appendChild(contentLabel);

    newSection.appendChild(document.createElement("br"));

    var content = document.createElement("textarea");
    content.setAttribute("class", "form-control");
    content.name = "content-" + index;
    content.rows = 5;
    content.required = true;
    newSection.appendChild(content);

    newSectionWrapper.appendChild(newSection);

    var addButton = document.createElement("button");
    addButton.setAttribute("class", "btn btn-primary m-3 add-button");
    addButton.type = "button";
    addButton.id = "add-button-" + (index + 1);
    addButton.onclick = addFunction;
    addButton.innerText = "+";
    newSectionWrapper.appendChild(addButton);

    for (var i = 0; i <= readOnlyChildren.length; i++) {
        if (i < index) {
            elements[i] = readOnlyChildren[i];
        } else if (i == index) {
            elements[i] = newSectionWrapper;
        } else if (i > index) {
            var element = readOnlyChildren[i - 1];
            editSection(i, element);
            elements[i] = element;
        }
    }

    sections.innerHTML = "";
    for (var k = 0; k < elements.length; k++) {
        sections.appendChild(elements[k]);
    }
}

function removeSection(pointer) {
    var index = parseInt(pointer.srcElement.id.replace("remove-button-", ""));
    var elements = [];
    var readOnlyChildren = sections.children;

    for (var i = 0; i < readOnlyChildren.length; i++) {
        if (i < index) {
            elements[i] = readOnlyChildren[i];
        } else if (i > index) {
            var element = readOnlyChildren[i];
            editSection(i - 1, element);
            elements[i - 1] = element;
        }
    }

    sections.innerHTML = "";
    for (var j = 0; j < elements.length; j++) {
        sections.appendChild(elements[j]);
    }
}

function editSection(i, element) {
    element.id = "section-" + i;

    var editSectionTitle = element.getElementsByClassName("section-title")[0];
    editSectionTitle.innerText = "Section " + (i + 1);

    var editRemoveButton = element.getElementsByClassName("remove-button")[0];
    editRemoveButton.id = "remove-button-" + i;

    var editAddButton = element.getElementsByClassName("add-button")[0];
    editAddButton.id = "add-button-" + i;

    var editSectionForm = element.getElementsByClassName("section-form")[0];
    var editSectionFormElements = editSectionForm.children;
    for (var j = 0; j < editSectionFormElements.length; j++) {
        if (typeof editSectionFormElements[j].name != "undefined") {
            var name = editSectionFormElements[j].name;
            editSectionFormElements[j].name = name.substring(0, name.indexOf("-") + 1) + i;
        }
    }
}