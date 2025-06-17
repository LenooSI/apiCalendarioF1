// Módulo para gerenciamento de estado global
const AppState = {
    teams: [],
    races: [],
    driverStats: {},
    initialize: async function() {
        try {
            const response = await fetch('api.php');
            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.error || 'Erro ao carregar dados da API');
            }
            const data = await response.json();
            this.teams = data.teams || [];
            this.races = data.races || [];
            this.driverStats = data.driverStats || {};

            // Inicializa os formulários após carregar os dados
            FormManager.initializeFormSelects();
            FormManager.initializeFormEvents();
            
            return true;
        } catch (error) {
            console.error("Erro ao carregar dados da API:", error.message);
            UIManager.showError("Não foi possível carregar os dados. Por favor, tente novamente mais tarde.");
            return false;
        }
    }
};

// Classe para gerenciamento de usuários
class UserManager {
    static getUsers() {
        try {
            const users = localStorage.getItem('users');
            return users ? JSON.parse(users) : [];
        } catch (error) {
            console.error('Erro ao recuperar usuários:', error);
            return [];
        }
    }

    static createUser(userData) {
        try {
            // Validação local
            if (!userData.username || !userData.password || !userData.name ||
                !userData.favoriteTeam || !userData.favoriteDriver) {
                throw new Error('Todos os campos são obrigatórios');
            }

            // Verifica se o usuário já existe
            const users = this.getUsers();
            if (users.some(user => user.username === userData.username)) {
                throw new Error('Este nome de usuário já está em uso');
            }

            // Adiciona o novo usuário
            const newUser = {
                ...userData,
                createdAt: new Date().toISOString()
            };
            users.push(newUser);
            localStorage.setItem('users', JSON.stringify(users));
            return newUser;
        } catch (error) {
            console.error('Erro ao criar usuário:', error);
            throw error;
        }
    }

    static getUserByUsername(username) {
        const users = this.getUsers();
        return users.find(user => user.username === username);
    }

    static updateUser(username, newData) {
        try {
            const users = this.getUsers();
            const index = users.findIndex(user => user.username === username);
            
            if (index === -1) {
                throw new Error('Usuário não encontrado');
            }

            // Atualiza os dados do usuário preservando o username e createdAt
            users[index] = {
                ...users[index],
                ...newData,
                username: users[index].username, // Mantém o username original
                createdAt: users[index].createdAt // Mantém a data de criação original
            };

            localStorage.setItem('users', JSON.stringify(users));
            return users[index];
        } catch (error) {
            console.error('Erro ao atualizar usuário:', error);
            throw error;
        }
    }

    static deleteUser(username) {
        try {
            const users = this.getUsers();
            const filteredUsers = users.filter(user => user.username !== username);
            
            if (filteredUsers.length === users.length) {
                throw new Error('Usuário não encontrado');
            }

            localStorage.setItem('users', JSON.stringify(filteredUsers));
        } catch (error) {
            console.error('Erro ao excluir usuário:', error);
            throw error;
        }
    }
}

// Classe para gerenciamento de formulários
class FormManager {
    static initializeFormEvents() {
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            // Remove qualquer listener existente antes de adicionar um novo
            const newForm = loginForm.cloneNode(true);
            loginForm.parentNode.replaceChild(newForm, loginForm);
            newForm.addEventListener('submit', this.handleFormSubmit.bind(this));
        }
    }

    static validateForm(formData) {
        const errors = [];
        
        // Não validar o username em modo de edição
        if (formData.mode !== 'edit') {
            if (!formData.username || formData.username.trim().length < 3) {
                errors.push('O nome de usuário deve ter pelo menos 3 caracteres');
            }
        }
        
        if (!formData.password || formData.password.length < 6) {
            errors.push('A senha deve ter pelo menos 6 caracteres');
        }
        
        if (!formData.name || formData.name.trim().length < 3) {
            errors.push('O nome completo deve ter pelo menos 3 caracteres');
        }
        
        if (!formData.favoriteTeam) {
            errors.push('Selecione uma equipe favorita');
        }
        
        if (!formData.favoriteDriver) {
            errors.push('Selecione um piloto favorito');
        }
        
        return errors;
    }

    static async handleFormSubmit(event) {
        event.preventDefault();
        const form = event.target;
        
        try {
            const formData = {
                username: form.username.value.trim(),
                password: form.password.value,
                name: form.name.value.trim(),
                favoriteTeam: form.favoriteTeam.value,
                favoriteDriver: form.favoriteDriver.value,
                mode: form.dataset.mode || 'create'
            };

            // Validação do formulário
            const errors = this.validateForm(formData);
            if (errors.length > 0) {
                UIManager.showError(errors.join('\n'));
                return;
            }

            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = 'Processando...';

            try {
                if (formData.mode === 'edit') {
                    // Modo de edição - não modifica o username
                    const updateData = { ...formData };
                    delete updateData.username; // Remove username do objeto de atualização
                    await UserManager.updateUser(formData.username, updateData);
                    UIManager.showSuccess('Usuário atualizado com sucesso!');
                } else {
                    // Modo de criação
                    await UserManager.createUser(formData);
                    UIManager.showSuccess('Usuário cadastrado com sucesso!');
                }

                // Limpa o formulário e fecha
                this.resetForm(form);
                UIManager.toggleLoginForm();
                UIManager.showUsersList();
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        } catch (error) {
            UIManager.showError(error.message);
        }
    }

    static setupEditForm(user) {
        const form = document.getElementById('loginForm');
        if (!form) return;

        // Configura o modo de edição
        form.dataset.mode = 'edit';

        // Preenche os campos
        const fields = ['username', 'password', 'name', 'favoriteTeam', 'favoriteDriver'];
        fields.forEach(field => {
            const input = form[field];
            if (input) {
                input.value = user[field] || '';
                if (field === 'username') {
                    input.disabled = true;
                }
            }
        });

        // Atualiza o texto do botão
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.textContent = 'Atualizar';
        }
    }

    static resetForm(form) {
        form = form || document.getElementById('loginForm');
        if (!form) return;

        // Limpa o formulário
        form.reset();
        
        // Reseta para o modo de criação
        form.dataset.mode = 'create';
        
        // Habilita o campo username
        const usernameInput = form['username'];
        if (usernameInput) {
            usernameInput.disabled = false;
        }

        // Reseta o texto do botão
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.textContent = 'Cadastrar';
        }
    }

    static initializeFormSelects() {
        this.populateTeamSelect();
        this.populateDriverSelect();
    }

    static populateTeamSelect() {
        const teamSelect = document.getElementById('favoriteTeam');
        if (!teamSelect) return;

        teamSelect.innerHTML = '<option value="">Selecione uma equipe</option>';
        AppState.teams.forEach(team => {
            const option = document.createElement('option');
            option.value = team.team;
            option.textContent = team.team;
            teamSelect.appendChild(option);
        });
    }

    static populateDriverSelect() {
        const driverSelect = document.getElementById('favoriteDriver');
        if (!driverSelect) return;

        driverSelect.innerHTML = '<option value="">Selecione um piloto</option>';
        Object.keys(AppState.driverStats).forEach(driverName => {
            const option = document.createElement('option');
            option.value = driverName;
            option.textContent = driverName;
            driverSelect.appendChild(option);
        });
    }
}

// Classe para gerenciamento de corridas
class RaceManager {
    static monthMap = {
        'Janeiro': 0, 'Fevereiro': 1, 'Março': 2, 'Abril': 3,
        'Maio': 4, 'Junho': 5, 'Julho': 6, 'Agosto': 7,
        'Setembro': 8, 'Outubro': 9, 'Novembro': 10, 'Dezembro': 11
    };

    static mostrarCorridas() {
        const container = document.getElementById('races-container');
        container.className = "race-container";
        const dataAtual = new Date();
        const ano = 2025;

        AppState.races.forEach(race => this.renderRace(race, container, dataAtual, ano));
    }

    static renderRace(race, container, dataAtual, ano) {
        if (!race.race) return; // Pula corridas sem informações completas

        const div = document.createElement('div');
        div.className = 'race';
        const raceId = race.race.replace(/\s+/g, '-').toLowerCase();
        const countdownId = `countdown-${raceId}`;
        const dataCorrida = this.parseRaceDate(race.date, ano);
        const corridaJaAconteceu = dataCorrida < dataAtual;

        div.innerHTML = this.generateRaceHTML(race, raceId, countdownId, corridaJaAconteceu);
        container.appendChild(div);

        if (!corridaJaAconteceu) {
            this.iniciarCronometro(countdownId, dataCorrida);
        }
    }

    static parseRaceDate(date, ano) {
        const [dia, , mes] = date.split(' ');
        return new Date(ano, this.monthMap[mes], parseInt(dia), 14, 0, 0);
    }

    static generateRaceHTML(race, raceId, countdownId, corridaJaAconteceu) {
        return `
            <strong>${race.race}</strong> - ${race.date}
            <div>
                <div id="countdown-container-${raceId}" class="countdown-container">
                    ${corridaJaAconteceu ?
                        '<span class="race-completed">Corrida já realizada</span>' :
                        `<div id="${countdownId}" class="countdown"></div>`
                    }
                </div>
                <button class="details-btn" onclick="UIManager.toggleImagem('${raceId}', '${race.url}')">Ver detalhes</button>
            </div>
            <div id="${raceId}" style="display: none; margin-top:10px;">
                <div class="race-layout">
                    <div class="race-img">
                        <img src="${race.url}" alt="Mapa do ${race.race}" style="width:100%; max-width:600px; border-radius:8px;" />
                    </div>
                    <div class="race-info-layout">
                        <div class="race-info">
                            <strong>Primeira Edição:</strong> ${race.primeiraEdicao}
                            <p><strong>Tempo Recorde de Volta:</strong> ${race.tempoRecorde}</p>
                            <strong>Comprimento do Circuito:</strong> ${race.comprimento}</p>
                            <strong>Número de Voltas:</strong> ${race.numVoltas}</p>
                            <strong>Maior Vencedor:</strong> ${race.maiorVencedor}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    static iniciarCronometro(id, dataCorrida) {
        const cronometroElement = document.getElementById(id);
        let interval;

        const atualizarCronometro = () => {
            const agora = new Date();
            const diferenca = dataCorrida - agora;

            if (diferenca <= 0) {
                clearInterval(interval);
                cronometroElement.innerHTML = 'Corrida em andamento ou já realizada';
                return;
            }

            const { dias, horas, minutos, segundos } = this.calcularTempo(diferenca);
            cronometroElement.innerHTML = this.generateCountdownHTML(dias, horas, minutos, segundos);
        };

        atualizarCronometro();
        interval = setInterval(atualizarCronometro, 1000);
    }

    static calcularTempo(diferenca) {
        return {
            dias: Math.floor(diferenca / (1000 * 60 * 60 * 24)),
            horas: Math.floor((diferenca % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
            minutos: Math.floor((diferenca % (1000 * 60 * 60)) / (1000 * 60)),
            segundos: Math.floor((diferenca % (1000 * 60)) / 1000)
        };
    }

    static generateCountdownHTML(dias, horas, minutos, segundos) {
        return `
            <div class="countdown-time">
                <span class="countdown-number">${dias}</span>
                <span class="countdown-text">dias</span>
            </div>
            <div class="countdown-time">
                <span class="countdown-number">${horas.toString().padStart(2, '0')}</span>
                <span class="countdown-text">horas</span>
            </div>
            <div class="countdown-time">
                <span class="countdown-number">${minutos.toString().padStart(2, '0')}</span>
                <span class="countdown-text">min</span>
            </div>
            <div class="countdown-time">
                <span class="countdown-number">${segundos.toString().padStart(2, '0')}</span>
                <span class="countdown-text">seg</span>
            </div>
        `;
    }
}

// Classe para gerenciamento da interface do usuário
class UIManager {
    static toggleImagem(id, url) {
        const imgDiv = document.getElementById(id);
        imgDiv.style.display = (imgDiv.style.display === 'none' || imgDiv.style.display === '') ? 'block' : 'none';
    }

    static toggleUsersList() {
        const usersSection = document.getElementById('usersSection');
        const overlay = document.getElementById('overlay');
        
        if (usersSection.style.display === 'none' || usersSection.style.display === '') {
            usersSection.style.display = 'block';
            overlay.style.display = 'block';
            this.showUsersList(); // Atualiza a lista quando abrir
        } else {
            usersSection.style.display = 'none';
            overlay.style.display = 'none';
        }
    }

    static showUsersList() {
        try {
            const users = UserManager.getUsers();
            const container = document.getElementById('users-list');
            
            if (!container) {
                throw new Error('Container da lista de usuários não encontrado');
            }

            if (users.length === 0) {
                container.innerHTML = '<div class="user-item">Nenhum usuário cadastrado</div>';
                return;
            }

            container.innerHTML = users.map(user => `
                <div class="user-item">
                    <div class="user-info">
                        <strong>${user.name}</strong> (${user.username})
                        <br>
                        Equipe: ${user.favoriteTeam} | Piloto: ${user.favoriteDriver}
                    </div>
                    <div class="user-actions">
                        <button onclick="UIManager.editUser('${user.username}')" class="edit-btn">Editar</button>
                        <button onclick="UIManager.confirmDeleteUser('${user.username}')" class="delete-btn">Excluir</button>
                    </div>
                </div>
            `).join('');
        } catch (error) {
            console.error('Erro ao exibir lista de usuários:', error);
            this.showError('Erro ao carregar lista de usuários');
        }
    }

    static confirmDeleteUser(username) {
        if (confirm(`Tem certeza que deseja excluir o usuário ${username}?`)) {
            try {
                UserManager.deleteUser(username);
                this.showSuccess('Usuário excluído com sucesso');
                this.showUsersList(); // Atualiza a lista após excluir
            } catch (error) {
                this.showError(error.message);
            }
        }
    }

    static editUser(username) {
        try {
            const user = UserManager.getUserByUsername(username);
            if (!user) {
                throw new Error('Usuário não encontrado');
            }

            this.toggleUsersList(); // Fecha a lista de usuários
            FormManager.setupEditForm(user); // Configura o formulário para edição
            this.toggleLoginForm(); // Abre o formulário em modo de edição
        } catch (error) {
            this.showError(error.message);
        }
    }

    static toggleLoginForm() {
        const formContainer = document.getElementById('loginFormContainer');
        const overlay = document.getElementById('overlay');
        
        if (formContainer.style.display === 'none' || formContainer.style.display === '') {
            formContainer.style.display = 'block';
            overlay.style.display = 'block';
        } else {
            formContainer.style.display = 'none';
            overlay.style.display = 'none';
            FormManager.resetForm(); // Reset form when closing
        }
    }

    static showUsersList() {
        try {
            const users = UserManager.getUsers();
            const container = document.getElementById('users-list');
            
            if (!container) {
                throw new Error('Container da lista de usuários não encontrado');
            }

            if (users.length === 0) {
                container.innerHTML = '<div class="user-item">Nenhum usuário cadastrado</div>';
                return;
            }

            container.innerHTML = users.map(user => `
                <div class="user-item">
                    <div class="user-info">
                        <strong>${user.name}</strong> (${user.username})
                        <br>
                        Equipe: ${user.favoriteTeam} | Piloto: ${user.favoriteDriver}
                    </div>
                    <div class="user-actions">
                        <button onclick="UIManager.editUser('${user.username}')" class="edit-btn">Editar</button>
                        <button onclick="UIManager.confirmDeleteUser('${user.username}')" class="delete-btn">Excluir</button>
                    </div>
                </div>
            `).join('');
        } catch (error) {
            console.error('Erro ao exibir lista de usuários:', error);
            this.showError('Erro ao carregar lista de usuários');
        }
    }

    static toggleEquipes() {
        const container = document.getElementById('teams-container');
        const button = document.getElementById('toggle-btn');
        if (container.style.display === 'none' || container.style.display === '') {
            container.style.display = 'block';
            button.textContent = 'Ocultar Equipes e Pilotos';
            container.innerHTML = '';
            this.mostrarEquipes();
        } else {
            container.style.display = 'none';
            button.textContent = 'Ver Equipes e Pilotos';
        }
    }

    static mostrarEquipes() {
        const container = document.getElementById('teams-container');
        AppState.teams.forEach(({ team, drivers, imagem, logo }) => {
            const div = document.createElement('div');
            div.className = 'team';
            div.innerHTML = this.generateTeamHTML(team, drivers, imagem, logo);
            container.appendChild(div);
        });
    }

    static generateTeamHTML(team, drivers, imagem, logo) {
        if (!drivers || drivers.length === 0) return ''; // Skip if no drivers data
        
        return `
            <h2>
                <img src="${logo}" alt="Logo ${team}">${team}
            </h2>
            <div class="team-layout">
                ${drivers[0] ? `
                    <button class="driver-btn" onclick="UIManager.mostrarDetalhes('${drivers[0].nome}')">
                        ${drivers[0].nome}
                        <img src="https://flagcdn.com/w40/${drivers[0].bandeira}.png" style="width:18px; vertical-align:middle; margin-left:6px;">
                    </button>
                ` : ''}
                <img src="${imagem}" alt="Carro da ${team}" class="car-img">
                ${drivers[1] ? `
                    <button class="driver-btn" onclick="UIManager.mostrarDetalhes('${drivers[1].nome}')">
                        ${drivers[1].nome}
                        <img src="https://flagcdn.com/w40/${drivers[1].bandeira}.png" style="width:18px; vertical-align:middle; margin-left:6px;">
                    </button>
                ` : ''}
            </div>
        `;
    }

    static mostrarDetalhes(nome) {
        const piloto = AppState.driverStats[nome];
        if (!piloto) return alert("Dados do piloto não encontrados.");

        const driverDiv = document.getElementById('driver-info');
        const overlay = document.getElementById('overlay');

        driverDiv.innerHTML = this.generateDriverDetailsHTML(nome, piloto);
        driverDiv.style.display = 'block';
        overlay.style.display = 'block';
    }

    static generateDriverDetailsHTML(nome, piloto) {
        return `
            <h3>${nome}</h3>
            <img src="${piloto.foto}" alt="${nome}">
            ${piloto.numero ? `<p>Numero: ${piloto.numero}</p>` : ''}
            ${piloto.idade ? `<p>Idade: ${piloto.idade} anos</p>` : ''}
            ${piloto.nacionalidade ? `<p>Nacionalidade: ${piloto.nacionalidade}</p>` : ''}
            ${piloto.titulos ? `<p>Títulos: ${piloto.titulos}</p>` : ''}
            <button onclick="UIManager.fecharDetalhes()">Fechar</button>
        `;
    }

    static fecharDetalhes() {
        document.getElementById('driver-info').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }

    static showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'notification error';
        errorDiv.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="notification-close">×</button>
            </div>
        `;
        document.body.appendChild(errorDiv);
        setTimeout(() => errorDiv.remove(), 5000);
    }

    static showSuccess(message) {
        const successDiv = document.createElement('div');
        successDiv.className = 'notification success';
        successDiv.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="notification-close">×</button>
            </div>
        `;
        document.body.appendChild(successDiv);
        setTimeout(() => successDiv.remove(), 5000);
    }
}

// Inicialização da aplicação
window.onload = async () => {
    if (await AppState.initialize()) {
        FormManager.initializeFormSelects();
        RaceManager.mostrarCorridas();
    }
};

// Event Listeners são inicializados no AppState.initialize
