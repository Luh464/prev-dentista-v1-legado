# Prev Dentista — Versão 1.0 (Código Legado & Estrutura Base)

### 🎓 Universidade Federal do Pará (UFPA)

**Faculdade de Sistemas de Informação — Campus Cametá**
**Disciplina:** Projeto Integrado II
**Professor:** Dr. Fabricio Farias

---

## 📄 Sobre este Repositório

Este repositório armazena o **código-fonte inicial** da primeira versão do sistema de gestão da clínica **Prev Dentista**.

O projeto funcionou como o marco zero para o desenvolvimento do trabalho final. Embora o sistema já estivesse passando pela transição de organização para o padrão arquitetural **MVC (Model-View-Controller)**, ele ainda sofria com severas limitações de escalabilidade. Todas as regras de negócio cruciais — tais como taxas de cartão de crédito, comissões de dentistas e critérios de rateio financeiro — encontravam-se rigidamente inseridas no código-fonte (*hardcoded*), restringindo o uso do software a apenas um cliente.

Este espaço serve como registro histórico do estado do software antes da implementação do Painel Administrativo Dinâmico e das regras de parametrização flexível exigidas na disciplina.

---

## 🔗 Evolução do Projeto

O ecossistema completo do projeto foi dividido para melhor avaliação das etapas de engenharia de software:

* 📂 **Versão Atual (Este Repositório):** Código base estruturado em MVC, mas com regras de negócio rígidas (*hardcoded*).
* 🚀 **Versão 2.0 (Repositório Refatorado):** Plataforma escalável com Painel Administrativo Dinâmico, CRUD de regras e Novo Modelo de Rateio Complexo (50/10/40).
  * [Acesse aqui o Repositório da Versão 2.0](https://github.com/Luh464/prev-dentista)
