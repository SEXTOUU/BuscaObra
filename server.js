const express = require("express");
const mercadopago = require("mercadopago");

const app = express();
app.use(express.json());

// Substitua "SEU_ACCESS_TOKEN" pelo seu token de acesso do Mercado Pago
mercadopago.configurations.setAccessToken("SEU_ACCESS_TOKEN");

// Endpoint para criar uma preferência de pagamento
app.post("/criar_pagamento", async (req, res) => {
    try {
        let preference = {
            items: [
                {
                    title: "Plano Premium",
                    unit_price: 10.0,
                    quantity: 1,
                }
            ],
            back_urls: {
                success: "https://www.seusite.com/sucesso",
                failure: "https://www.seusite.com/falha",
                pending: "https://www.seusite.com/pendente",
            },
            auto_return: "approved",
        };

        const response = await mercadopago.preferences.create(preference);
        res.json({ id: response.body.id });
    } catch (error) {
        console.error("Erro ao criar pagamento:", error);
        if (error.response) {
            console.error("Detalhes do erro:", error.response);
        }
        res.status(500).json({ error: "Erro ao criar pagamento" });
    }
});

// Inicia o servidor
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Servidor rodando na porta ${PORT}`);
});
