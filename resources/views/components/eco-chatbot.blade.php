{{-- resources/views/components/eco-chatbot.blade.php --}}
@props(['title' => 'EcoChatbot', 'placeholder' => 'Posez une question sur l\'écologie...'])

<div class="eco-chatbot" x-data="ecoChatbot()" x-init="initChatbot">
    <div class="eco-chatbot-toggle" x-on:click="toggleChatbot">
        <img src="{{ asset('assets/img/chatbot-robot.svg') }}" alt="EcoBot" class="chatbot-icon">
        <span class="chat-text">EcoBot</span>
    </div>

    <div class="eco-chatbot-container" x-show="isOpen" x-transition>
        <div class="eco-chatbot-header">
            <div class="d-flex align-items-center">
                <img src="{{ asset('assets/img/chatbot-robot.svg') }}" alt="Assistant" class="chatbot-header-icon me-2">
                <h5 class="m-0">EcoBot</h5>
            </div>
            <button class="btn-close" x-on:click="toggleChatbot"></button>
        </div>

        <div class="eco-chatbot-messages" x-ref="messagesContainer">
            <div class="eco-chatbot-message bot">
                <div class="avatar">
                    <img src="{{ asset('assets/img/chatbot-robot.svg') }}" alt="Assistant" class="avatar-icon">
                </div>
                <div class="eco-chatbot-bubble">
                    Bonjour ! Je suis votre assistant écologique. Comment puis-je vous aider aujourd'hui ?
                </div>
            </div>

            <template x-for="(message, index) in messages" :key="index">
                <div :class="`eco-chatbot-message ${message.sender}`">
                    <template x-if="message.sender === 'bot'">
                        <div class="avatar">
                            <img src="{{ asset('assets/img/chatbot-robot.svg') }}" alt="Assistant" class="avatar-icon">
                        </div>
                    </template>
                    <div class="eco-chatbot-bubble" x-html="formatMessage(message.text)"></div>
                    <template x-if="message.sender === 'user'">
                        <div class="avatar user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                    </template>
                </div>
            </template>

            <div class="eco-chatbot-typing" x-show="isLoading">
                <div class="avatar">
                    <img src="{{ asset('assets/img/chatbot-robot.svg') }}" alt="Assistant" class="avatar-icon">
                </div>
                <div class="eco-chatbot-bubble typing-bubble">
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
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
                    >
                    <button class="btn send-btn" type="submit" :disabled="isLoading || !userInput.trim()">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.eco-chatbot {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
    font-family: var(--bs-font-sans-serif, "Noto Sans", "Open Sans");
}

.eco-chatbot-toggle {
    width: 140px;
    height: 50px;
    border-radius: 25px;
    background: var(--bs-primary, #774dd3);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(119, 77, 211, 0.3);
    transition: all 0.3s ease;
    animation: pulse 2s infinite;
    font-family: var(--bs-font-sans-serif, "Noto Sans", "Open Sans");
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(119, 77, 211, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(119, 77, 211, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(119, 77, 211, 0);
    }
}

.eco-chatbot-toggle:hover {
    transform: scale(1.1);
}

.chat-text {
    font-size: 16px;
    font-weight: 600;
    letter-spacing: 0.5px;
    font-family: var(--bs-font-sans-serif, "Noto Sans", "Open Sans");
}

.chatbot-icon {
    width: 40px;
    height: 40px;
    margin-right: 8px;
}

.chatbot-header-icon {
    width: 28px;
    height: 28px;
}

.avatar-icon {
    width: 28px;
    height: 28px;
}

.eco-chatbot-container {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 350px;
    height: 500px;
    background-color: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(119, 77, 211, 0.2);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    border: 1px solid rgba(119, 77, 211, 0.1);
    transition: all 0.3s ease;
}

.eco-chatbot-header {
    background: var(--bs-primary, #774dd3);
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: bold;
    font-family: var(--bs-font-sans-serif, "Noto Sans", "Open Sans");
}

.eco-chatbot-header i {
    font-size: 20px;
}

.eco-chatbot-header .btn-close {
    background-color: transparent;
    color: white;
    opacity: 0.8;
    font-size: 20px;
}

.eco-chatbot-header .btn-close:hover {
    opacity: 1;
}

.eco-chatbot-messages {
    flex: 1;
    padding: 16px;
    overflow-y: auto;
    background-color: #f8f9fa;
    display: flex;
    flex-direction: column;
    gap: 16px;
    scrollbar-width: thin;
    scrollbar-color: rgba(119, 77, 211, 0.2) transparent;
}

.eco-chatbot-messages::-webkit-scrollbar {
    width: 6px;
}

.eco-chatbot-messages::-webkit-scrollbar-track {
    background: transparent;
}

.eco-chatbot-messages::-webkit-scrollbar-thumb {
    background-color: rgba(119, 77, 211, 0.2);
    border-radius: 6px;
}

.eco-chatbot-message {
    display: flex;
    margin-bottom: 12px;
    align-items: flex-start;
    position: relative;
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.eco-chatbot-message.user {
    flex-direction: row-reverse;
}

.avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: var(--bs-primary, #774dd3);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 8px;
    flex-shrink: 0;
}

.eco-chatbot-message.user .avatar {
    margin-left: 8px;
    margin-right: 0;
}

.user-avatar {
    background-color: var(--bs-secondary, #64748b);
}

.eco-chatbot-bubble {
    max-width: 85%;
    padding: 12px 16px;
    border-radius: 18px;
    position: relative;
    word-break: break-word;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    line-height: 1.5;
    font-size: 14px;
}

.eco-chatbot-message.bot .eco-chatbot-bubble {
    background-color: white;
    color: #333;
    border-top-left-radius: 4px;
    position: relative;
}

.eco-chatbot-message.bot .eco-chatbot-bubble::after {
    content: '';
    position: absolute;
    left: -6px;
    top: 0;
    width: 10px;
    height: 10px;
    background: white;
    transform: rotate(45deg);
}

.eco-chatbot-message.user .eco-chatbot-bubble {
    background-color: var(--bs-primary, #774dd3);
    color: white;
    border-top-right-radius: 4px;
    position: relative;
}

.eco-chatbot-message.user .eco-chatbot-bubble::after {
    content: '';
    position: absolute;
    right: -6px;
    top: 0;
    width: 10px;
    height: 10px;
    background: var(--bs-primary, #774dd3);
    transform: rotate(45deg);
}

.eco-chatbot-input {
    padding: 16px;
    background-color: #f8f9fa;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    position: relative;
}

.eco-chatbot-input .form-control {
    border-radius: 24px;
    border: 1px solid #e0e0e0;
    padding: 12px 18px;
    box-shadow: none;
    background-color: white;
    transition: all 0.2s ease;
    font-size: 14px;
}

.eco-chatbot-input .form-control:focus {
    box-shadow: 0 0 0 3px rgba(119, 77, 211, 0.15);
    border-color: var(--bs-primary, #774dd3);
}

.eco-chatbot-input .input-group {
    position: relative;
}

.eco-chatbot-input .send-btn {
    position: absolute;
    right: 5px;
    top: 5px;
    bottom: 5px;
    border-radius: 50%;
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--bs-primary, #774dd3);
    border: none;
    color: white;
    z-index: 5;
}

.eco-chatbot-input .send-btn:hover {
    background-color: var(--bs-link-hover-color, #522aaa);
}

.eco-chatbot-input .send-btn:disabled {
    background-color: #bdc3c7;
    cursor: not-allowed;
}

.eco-chatbot-typing {
    display: flex;
    margin-bottom: 10px;
    align-items: flex-start;
}

.typing-bubble {
    background-color: white;
    min-width: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.typing-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: var(--bs-primary, #774dd3);
    margin-right: 4px;
    animation: typing 1.4s infinite both;
}

.typing-dot:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dot:nth-child(3) {
    animation-delay: 0.4s;
    margin-right: 0;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
    }
    30% {
        transform: translateY(-6px);
    }
}

@media (max-width: 576px) {
    .eco-chatbot-container {
        width: 100%;
        height: 100%;
        bottom: 0;
        right: 0;
        border-radius: 0;
        position: fixed;
    }
    
    .eco-chatbot-toggle {
        width: 120px;
        height: 45px;
    }
    
    .chatbot-icon {
        width: 30px;
        height: 30px;
    }
    
    .eco-chatbot-input {
        padding: 12px;
    }
    
    .eco-chatbot-header {
        padding: 12px 16px;
    }
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
        },
        
        toggleChatbot() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.$nextTick(() => {
                    this.scrollToBottom();
                });
            }
        },
        
        sendMessage() {
            if (!this.userInput.trim() || this.isLoading) return;
            
            // Add user message
            this.messages.push({
                sender: 'user',
                text: this.userInput.trim()
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
                    text: data.response
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
                    text: "Désolé, je n'ai pas pu traiter votre demande. Veuillez réessayer plus tard."
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
            return text.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>');
        }
    };
}
</script>