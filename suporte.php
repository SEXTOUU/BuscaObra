<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suporte - BuscaObra</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/suporte.css"> <!-- Certifique-se de que este CSS contenha as definições necessárias -->
</head>
<body>
    <header class="header text-center">
        <h1>Suporte</h1>
        <p class="lead">Estamos aqui para ajudar você!</p>
    </header>

    <section class="contact-section container my-5">
        <h2 class="text-center text-dark mb-4">Entre em Contato</h2>
        <p class="text-center">Se você tiver alguma dúvida ou precisar de assistência, preencha o formulário abaixo:</p>
        <form>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" id="name" placeholder="Seu Nome" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Seu Email" required>
                </div>
            </div>
            <div class="form-group">
                <label for="message">Mensagem</label>
                <textarea class="form-control" id="message" rows="4" placeholder="Digite sua mensagem aqui..." required></textarea>
            </div>
            <div class="form-group">
                <label for="issueType">Tipo de Suporte</label>
                <select class="form-control" id="issueType" required>
                    <option value="" disabled selected>Selecione...</option>
                    <option value="technical">Suporte Técnico</option>
                    <option value="account">Conta e Acesso</option>
                    <option value="general">Dúvida Geral</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </section>

    <section class="faq-section container my-5">
        <h2 class="text-center text-dark mb-4">Perguntas Frequentes</h2>
        <div class="accordion" id="faqAccordion">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Como me cadastro na plataforma?
                        </button>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#faqAccordion">
                    <div class="card-body">
                        Você pode se cadastrar clicando no botão de cadastro na página inicial e preenchendo o formulário com suas informações.
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Como posso editar meu perfil?
                        </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#faqAccordion">
                    <div class="card-body">
                        Você pode editar seu perfil acessando a seção "Meu Perfil" após fazer login e clicando em "Editar Perfil".
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingThree">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            O que fazer se esquecer minha senha?
                        </button>
                    </h5>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#faqAccordion">
                    <div class="card-body">
                        Você pode redefinir sua senha clicando em "Esqueci minha senha" na página de login e seguindo as instruções.
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingFour">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Como posso garantir que meu perfil seja visto pelos empregadores?
                    </button>
                </h5>
            </div>
            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#faqAccordion">
                <div class="card-body">
                    Para garantir que seu perfil seja visto, mantenha suas informações atualizadas, adicione uma foto profissional e complete todas as seções do seu perfil, incluindo suas habilidades e experiências anteriores.
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingFive">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        O que fazer se eu encontrar um trabalho que não é adequado para mim?
                    </button>
                </h5>
            </div>
            <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#faqAccordion">
                <div class="card-body">
                    Se você encontrar uma vaga que não é adequada, recomendamos que você não se candidate. Você pode filtrar as vagas que aparecem para você de acordo com suas preferências na seção de busca de empregos.
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingSix">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                        Como posso cancelar meu cadastro?
                    </button>
                </h5>
            </div>
            <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#faqAccordion">
                <div class="card-body">
                    Para cancelar seu cadastro, entre em contato com nossa equipe de suporte através do formulário de contato e solicite o cancelamento da sua conta. Lembre-se de que essa ação é irreversível.
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingSeven">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                        É possível encontrar trabalho em outras áreas além da construção civil?
                    </button>
                </h5>
            </div>
            <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#faqAccordion">
                <div class="card-body">
                    Atualmente, o *BuscaObra* é focado exclusivamente na construção civil. No entanto, estamos sempre avaliando a possibilidade de expandir para outras áreas no futuro.
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingEight">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                        Quais são as formas de pagamento disponíveis para os empregadores?
                    </button>
                </h5>
            </div>
            <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#faqAccordion">
                <div class="card-body">
                    Os empregadores podem realizar pagamentos através de cartão de crédito, transferência bancária e, em alguns casos, via PayPal. As opções podem variar de acordo com a região.
                </div>
            </div>
        </div>
        
    </section>

    <section class="contact-info-section container my-5">
        <h2 class="text-center text-dark mb-4">Informações de Contato</h2>
        <p class="text-center">Você também pode entrar em contato conosco através das seguintes opções:</p>
        <ul class="list-unstyled text-center">
            <li><strong>Email:</strong> suporte@buscaobra.com.br</li>
            <li><strong>Telefone:</strong> (11) 1234-5678</li>
            <li><strong>WhatsApp:</strong> (11) 98765-4321</li>
        </ul>
    </section>

    <footer class="footer bg-dark text-white text-center py-4">
        <p>&copy; 2024 BuscaObra. Todos os direitos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
