document.getElementById("enviar").addEventListener("click", function () {
    const arquivo = document.getElementById("arquivo").files[0];
    const resultado = document.getElementById("resultado");

    if (!arquivo) {
        resultado.innerText = "Selecione um arquivo";
        return;
    }

    const formData = new FormData();
    formData.append("arquivo", arquivo);

    fetch("upload.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === "ok") {
                resultado.innerText = `Arquivo enviado com sucesso! Nome: ${data.nome}, Tipo: ${data.tipo}`;
            } else {
                resultado.innerText = data.msg;
            }
        })
        .catch(error => {
            console.error(error);
            resultado.innerText = "Erro ao enviar arquivo";
        });
});