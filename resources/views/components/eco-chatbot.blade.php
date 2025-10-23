{{-- resources/views/components/eco-chatbot.blade.php --}}
@props(['title' => 'EcoChatbot', 'placeholder' => 'Posez une question sur l\'écologie...'])

<div class="eco-chatbot" x-data="ecoChatbot()" x-init="initChatbot">
    <div class="eco-chatbot-toggle" x-on:click="toggleChatbot">
        <div class="chatbot-toggle-content">
            <img src="{{ asset('assets/img/chatbot-robot.svg') }}" class="chatbot-icon" alt="EcoBot" width="24" height="24">
            <span class="chat-text">EcoBot</span>
        </div>
        <div class="chatbot-pulse"></div>
    </div>

    <div class="eco-chatbot-container" x-show="isOpen" x-transition:enter="chatbot-enter" x-transition:enter-start="chatbot-enter-start" x-transition:enter-end="chatbot-enter-end">
        <div class="eco-chatbot-header">
            <div class="d-flex align-items-center">
                <div class="chatbot-header-avatar">
                    <img src="{{ asset('assets/img/chatbot-robot.svg') }}" alt="EcoBot" width="24" height="24">
                </div>
                <div class="ms-3">
                    <h5 class="m-0 text-bright-white">EcoBot</h5>
                    <small class="text-success-bright">En ligne</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-outline-light" x-on:click="clearChat">
                    <i class="fas fa-trash"></i>
                </button>
                <button class="btn-close btn-close-white" x-on:click="toggleChatbot"></button>
            </div>
        </div>

        <div class="eco-chatbot-messages" x-ref="messagesContainer">
            <div class="eco-chatbot-message bot">
                <div class="avatar">
                    <img src="{{ asset('assets/img/chatbot-robot.svg') }}" alt="EcoBot" width="24" height="24">
                </div>
                <div class="eco-chatbot-bubble">
                    <div class="message-content">
                        <strong>Bonjour ! 🌱</strong><br>
                        Je suis votre assistant écologique EcoBot. Je peux vous aider avec :
                        <div class="quick-suggestions mt-2">
                            <span class="suggestion-tag" x-on:click="userInput = 'Comment réduire mon empreinte carbone ?'; sendMessage()">Réduction carbone</span>
                            <span class="suggestion-tag" x-on:click="userInput = 'Quels sont les gestes écologiques au quotidien ?'; sendMessage()">Gestes écologiques</span>
                            <span class="suggestion-tag" x-on:click="userInput = 'Comment participer à des événements éco-responsables ?'; sendMessage()">Événements éco</span>
                        </div>
                    </div>
                </div>
            </div>

            <template x-for="(message, index) in messages" :key="index">
                <div :class="`eco-chatbot-message ${message.sender}`">
                    <template x-if="message.sender === 'bot'">
                        <div class="avatar">
                            <img src="{{ asset('assets/img/chatbot-robot.svg') }}" alt="EcoBot" width="24" height="24">
                        </div>
                    </template>
                    <div class="eco-chatbot-bubble">
                        <div class="message-content" x-html="formatMessage(message.text)"></div>
                        <div class="message-time" x-text="getCurrentTime()"></div>
                    </div>
                    <template x-if="message.sender === 'user'">
                        <div class="avatar user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                    </template>
                </div>
            </template>

            <div class="eco-chatbot-typing" x-show="isLoading">
                <div class="avatar">
                    <img src="{{ asset('assets/img/chatbot-robot.svg') }}" alt="EcoBot" width="24" height="24">
                </div>
                <div class="eco-chatbot-bubble typing-bubble">
                    <div class="typing-indicator">
                        <span>EcoBot réfléchit</span>
                        <span class="typing-dot"></span>
                        <span class="typing-dot"></span>
                        <span class="typing-dot"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="eco-chatbot-input">
            <form x-on:submit.prevent="sendMessage">
                <div class="input-group">
                    <input 
                        type="text" 
                        class="form-control" 
                        placeholder="{{ $placeholder }}" 
                        x-model="userInput"
                        :disabled="isLoading"
                        x-on:keydown.enter="sendMessage"
                        x-ref="chatInput"
                    >
                    <button class="btn send-btn" type="submit" :disabled="isLoading || !userInput.trim()">
                        <i class="fas fa-paper-plane" x-show="!isLoading"></i>
                        <i class="fas fa-spinner fa-spin" x-show="isLoading"></i>
                    </button>
                </div>
            </form>
            <div class="quick-actions mt-2">
                <small class="text-muted">Suggestions rapides :</small>
                <div class="d-flex gap-1 mt-1 flex-wrap">
                    <span class="quick-action" x-on:click="userInput = 'Conseils pour le tri sélectif'; sendMessage()">♻️ Tri</span>
                    <span class="quick-action" x-on:click="userInput = 'Économies d\\'énergie'; sendMessage()">⚡ Énergie</span>
                    <span class="quick-action" x-on:click="userInput = 'Transport écologique'; sendMessage()">🚲 Transport</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.eco-chatbot {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Toggle Button */
.eco-chatbot-toggle {
    position: relative;
    width: 140px;
    height: 50px;
    border-radius: 25px;
    background: linear-gradient(135deg, var(--color-success-dark) 0%, #43a047 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3);
    transition: all 0.3s ease;
    overflow: hidden;
    border: 2px solid rgba(255, 255, 255, 0.1);
}

.eco-chatbot-toggle:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(76, 175, 80, 0.4);
}

.chatbot-toggle-content {
    display: flex;
    align-items: center;
    gap: 8px;
    z-index: 2;
}

.chatbot-icon {
    width: 24px;
    height: 24px;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
}

.chat-text {
    font-size: 16px;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

.chatbot-pulse {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 25px;
    background: linear-gradient(135deg, transparent 0%, rgba(255, 255, 255, 0.1) 100%);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        opacity: 0.6;
        transform: scale(1);
    }
    50% {
        opacity: 0.3;
        transform: scale(1.05);
    }
    100% {
        opacity: 0.6;
        transform: scale(1);
    }
}

/* Chat Container */
.eco-chatbot-container {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 380px;
    height: 520px;
    background: var(--color-section-dark);
    border-radius: 16px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    border: 1px solid var(--color-border-light);
    backdrop-filter: blur(10px);
}

.chatbot-enter-start {
    opacity: 0;
    transform: translateY(20px) scale(0.9);
}

.chatbot-enter-end {
    opacity: 1;
    transform: translateY(0) scale(1);
}

/* Header */
.eco-chatbot-header {
    background: linear-gradient(135deg, var(--color-success-dark) 0%, #388e3c 100%);
    color: white;
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--color-border-light);
}

.chatbot-header-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

/* Messages Area */
.eco-chatbot-messages {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    background: var(--color-dark-main-bg);
    display: flex;
    flex-direction: column;
    gap: 16px;
    scrollbar-width: thin;
    scrollbar-color: var(--color-success-dark) transparent;
}

.eco-chatbot-messages::-webkit-scrollbar {
    width: 6px;
}

.eco-chatbot-messages::-webkit-scrollbar-track {
    background: transparent;
}

.eco-chatbot-messages::-webkit-scrollbar-thumb {
    background-color: var(--color-success-dark);
    border-radius: 6px;
}

/* Messages */
.eco-chatbot-message {
    display: flex;
    margin-bottom: 12px;
    align-items: flex-start;
    gap: 12px;
    animation: messageSlide 0.3s ease-out;
}

@keyframes messageSlide {
    from {
        opacity: 0;
        transform: translateX(10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.eco-chatbot-message.user {
    flex-direction: row-reverse;
}

.avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--color-success-dark) 0%, #43a047 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 14px;
    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
}

.avatar img {
    width: 24px;
    height: 24px;
    filter: brightness(1.2);
}

.user-avatar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Message Bubbles */
.eco-chatbot-bubble {
    max-width: 75%;
    padding: 12px 16px;
    border-radius: 18px;
    position: relative;
    word-break: break-word;
    line-height: 1.5;
    font-size: 14px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.eco-chatbot-message.bot .eco-chatbot-bubble {
    background: var(--color-section-dark);
    color: var(--color-success-bright);
    border: 1px solid var(--color-border-light);
    border-top-left-radius: 4px;
}

.eco-chatbot-message.user .eco-chatbot-bubble {
    background: linear-gradient(135deg, var(--color-success-dark) 0%, #43a047 100%);
    color: white;
    border-top-right-radius: 4px;
}

.message-content {
    line-height: 1.5;
}

.message-content a {
    color: var(--color-info-bright);
    text-decoration: underline;
}

.message-time {
    font-size: 11px;
    opacity: 0.7;
    margin-top: 4px;
}

/* Quick Suggestions */
.quick-suggestions {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 8px;
}

.suggestion-tag {
    background: rgba(76, 175, 80, 0.1);
    border: 1px solid rgba(76, 175, 80, 0.3);
    color: var(--color-success-bright);
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.suggestion-tag:hover {
    background: rgba(76, 175, 80, 0.2);
    transform: translateY(-1px);
}

/* Input Area */
.eco-chatbot-input {
    padding: 16px 20px;
    background: var(--color-section-dark);
    border-top: 1px solid var(--color-border-light);
}

.eco-chatbot-input .form-control {
    border-radius: 20px;
    border: 1px solid var(--color-border-light);
    padding: 12px 50px 12px 16px;
    background: var(--color-dark-main-bg);
    color: var(--color-success-bright);
    font-size: 14px;
    transition: all 0.2s ease;
}

.eco-chatbot-input .form-control:focus {
    box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.15);
    border-color: var(--color-success-dark);
    background: var(--color-section-dark);
}

.eco-chatbot-input .form-control::placeholder {
    color: rgba(255, 255, 255, 0.4);
}

.eco-chatbot-input .input-group {
    position: relative;
}

.eco-chatbot-input .send-btn {
    position: absolute;
    right: 6px;
    top: 6px;
    bottom: 6px;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--color-success-dark) 0%, #43a047 100%);
    border: none;
    color: white;
    z-index: 5;
    transition: all 0.2s ease;
}

.eco-chatbot-input .send-btn:hover:not(:disabled) {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
}

.eco-chatbot-input .send-btn:disabled {
    background: var(--color-border-light);
    cursor: not-allowed;
    transform: none;
}

/* Quick Actions */
.quick-actions {
    font-size: 12px;
}

.quick-action {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--color-border-light);
    color: var(--color-success-bright);
    padding: 4px 8px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 11px;
}

.quick-action:hover {
    background: rgba(76, 175, 80, 0.1);
    border-color: var(--color-success-dark);
}

/* Typing Indicator */
.eco-chatbot-typing {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.typing-bubble {
    background: var(--color-section-dark);
    border: 1px solid var(--color-border-light);
    min-width: 100px;
}

.typing-indicator {
    display: flex;
    align-items: center;
    gap: 4px;
    color: var(--color-success-bright);
    font-size: 12px;
}

.typing-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: var(--color-success-bright);
    animation: typing 1.4s infinite both;
}

.typing-dot:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dot:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
        opacity: 0.4;
    }
    30% {
        transform: translateY(-4px);
        opacity: 1;
    }
}

/* Responsive Design */
@media (max-width: 576px) {
    .eco-chatbot {
        bottom: 10px;
        right: 10px;
    }
    
    .eco-chatbot-container {
        width: calc(100vw - 20px);
        height: calc(100vh - 100px);
        bottom: 70px;
        right: 10px;
        border-radius: 12px;
    }
    
    .eco-chatbot-toggle {
        width: 120px;
        height: 45px;
    }
    
    .eco-chatbot-messages {
        padding: 16px;
    }
    
    .eco-chatbot-input {
        padding: 12px 16px;
    }
    
    .eco-chatbot-header {
        padding: 12px 16px;
    }
}

/* Text Colors from Your Theme */
.text-bright-white { 
    color: #fafafa !important; 
}

.text-muted {
    color: rgba(255, 255, 255, 0.6) !important;
}

.text-success-bright { 
    color: var(--color-success-bright) !important; 
}

.text-info-bright { 
    color: var(--color-info-bright) !important; 
}
</style>

<script>
function ecoChatbot() {
    return {
        isOpen: false,
        userInput: '',
        messages: [],
        isLoading: false,
        
        initChatbot() {
            // Check if there are stored messages
            const storedMessages = localStorage.getItem('ecoChatbotMessages');
            if (storedMessages) {
                this.messages = JSON.parse(storedMessages);
                
                // Scroll to bottom on init if there are messages
                this.$nextTick(() => {
                    this.scrollToBottom();
                });
            }
            
            // Auto-focus input when chatbot opens
            this.$watch('isOpen', (value) => {
                if (value) {
                    this.$nextTick(() => {
                        this.$refs.chatInput.focus();
                    });
                }
            });
        },
        
        toggleChatbot() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.$nextTick(() => {
                    this.scrollToBottom();
                    this.$refs.chatInput.focus();
                });
            }
        },
        
        sendMessage() {
            if (!this.userInput.trim() || this.isLoading) return;
            
            // Add user message
            this.messages.push({
                sender: 'user',
                text: this.userInput.trim(),
                timestamp: new Date()
            });
            
            // Save the message to send to API
            const messageToSend = this.userInput.trim();
            
            // Clear input
            this.userInput = '';
            
            // Save to localStorage
            this.saveMessages();
            
            // Scroll to bottom
            this.$nextTick(() => {
                this.scrollToBottom();
            });
            
            // Set loading state
            this.isLoading = true;
            
            // Send to API
            fetch('/api/eco-chatbot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message: messageToSend
                })
            })
            .then(response => response.json())
            .then(data => {
                // Add bot response
                this.messages.push({
                    sender: 'bot',
                    text: data.response,
                    timestamp: new Date()
                });
                
                // Save to localStorage
                this.saveMessages();
                
                // Scroll to bottom
                this.$nextTick(() => {
                    this.scrollToBottom();
                });
            })
            .catch(error => {
                console.error('Error:', error);
                // Add error message
                this.messages.push({
                    sender: 'bot',
                    text: "Désolé, je n'ai pas pu traiter votre demande. Veuillez réessayer plus tard.",
                    timestamp: new Date()
                });
                
                // Save to localStorage
                this.saveMessages();
                
                // Scroll to bottom
                this.$nextTick(() => {
                    this.scrollToBottom();
                });
            })
            .finally(() => {
                this.isLoading = false;
            });
        },
        
        clearChat() {
            this.messages = [];
            localStorage.removeItem('ecoChatbotMessages');
        },
        
        scrollToBottom() {
            if (this.$refs.messagesContainer) {
                this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
            }
        },
        
        saveMessages() {
            // Keep only last 20 messages to avoid localStorage size issues
            const messagesToSave = this.messages.slice(-20);
            localStorage.setItem('ecoChatbotMessages', JSON.stringify(messagesToSave));
        },
        
        formatMessage(text) {
            // Convert URLs to clickable links
            return text.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-info-bright">$1</a>');
        },
        
        getCurrentTime() {
            return new Date().toLocaleTimeString('fr-FR', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
        }
    };
}
</script>