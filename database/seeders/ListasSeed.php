<?php

namespace Database\Seeders;

use App\Models\ListasModel;
use Illuminate\Database\Seeder;

class ListasSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            /* Tipos de valor para las listas desplegables */
            array('tipo' => 'tipo_valor_listas', 'nombre' => 'ID'),
            array('tipo' => 'tipo_valor_listas', 'nombre' => 'VALOR NÚMERICO'),
            array('tipo' => 'tipo_valor_listas', 'nombre' => 'VALOR TEXTO'),

            /* Tipos de listas desplegables*/
            array('tipo' => 'tipo_listas', 'nombre' => 'ACTUACIONES (ACCIONES DE PREVENCIÓN Y CONTROL)', 'tipo_valor' => 3, 'valor_texto' => 'actuacion_vee'),
            array('tipo' => 'tipo_listas', 'nombre' => 'PROFESIONES (IMPARCIALIDAD Y CONFLICTO)', 'tipo_valor' => 3, 'valor_texto' => 'profesiones'),
            array('tipo' => 'tipo_listas', 'nombre' => 'ETAPA (CRONOGRAMA PLAN DE GESTIÓN)', 'tipo_valor' => 3, 'valor_texto' => 'etapas_crono'),
            array('tipo' => 'tipo_listas', 'nombre' => 'ACTUACIONES (ACTUACIONES POSTERIORES)', 'tipo_valor' => 3, 'valor_texto' => 'actuaciones_posteriores'),
            //array('tipo' => 'tipo_listas', 'nombre' => 'ACCIONES INTERNAS (ACTUACIONES POSTERIORES)', 'tipo_valor' => 3, 'valor_texto' => 'acciones_internas'),

            /* Actuaciones para crear veedurias*/
            array('tipo' => 'actuacion_vee', 'tipo_valor' => 2, 'valor_numero' => 1, 'nombre' => 'ACCIÓN DE PREVENCIÓN Y CONTROL A LA FUNCIÓN PÚBLICA'),
            array('tipo' => 'actuacion_vee', 'tipo_valor' => 2, 'valor_numero' => 2, 'nombre' => 'SEGUIMIENTO A LOS RESULTADOS DE UNA ACCIÓN DE PREVENCIÓN Y CONTROL A LA FUNCIÓN PÚBLICA'),
            //array('tipo' => 'actuacion_vee', 'tipo_valor' => 2, 'valor_numero' => 3, 'nombre' => 'SEGUIMIENTOS DIFERENTES AL DE INFORMES DE PREVENCIÓN Y CONTROL A LA FUNCIÓN PÚBLICA'),
            //array('tipo' => 'actuacion_vee', 'tipo_valor' => 2, 'valor_numero' => 5, 'nombre' => 'REVISIONES CONTRACTUALES'),

            /* Profesiones */
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 1, 'nombre' => 'ABOGADO'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 2, 'nombre' => 'ADMINISTRACIÓN DE EMPRESAS'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 3, 'nombre' => 'ADMINISTRACIÓN DE NEGOCIOS'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 4, 'nombre' => 'ADMINISTRACIÓN FINANCIERA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 5, 'nombre' => 'ADMINISTRACIÓN PÚBLICA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 6, 'nombre' => 'ANTROPOLOGÍA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 7, 'nombre' => 'ARQUITECTURA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 8, 'nombre' => 'AUDITOR FINACIERO Y CONTABLE'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 9, 'nombre' => 'BACTERIOLOGIA Y LABORATORIO CLINICO'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 10, 'nombre' => 'BIBLIOTECOLOGÍA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 11, 'nombre' => 'BIOLOGÍA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 12, 'nombre' => 'BOTANICO'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 13, 'nombre' => 'CARTÓGRAFÍA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 14, 'nombre' => 'CIENCIA POLÍTICA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 15, 'nombre' => 'COMUNICACIÓN SOCIAL'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 16, 'nombre' => 'CONTADURÍA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 17, 'nombre' => 'DIETISTA Y NUTRICIONISTA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 18, 'nombre' => 'DISEÑO GRÁFICO'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 19, 'nombre' => 'DISEÑO INDUSTRIAL'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 20, 'nombre' => 'ECONOMÍA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 21, 'nombre' => 'ENFERMERÍA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 22, 'nombre' => 'FILOSOFÍA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 23, 'nombre' => 'FÍSICO'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 24, 'nombre' => 'FISIOTERAPEUTA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 25, 'nombre' => 'GEOLOGÍA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 26, 'nombre' => 'INGENIERIA AGRICOLA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 27, 'nombre' => 'INGENIERÍA AGRONÓMICA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 28, 'nombre' => 'INGENIERÍA AMBIENTAL'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 29, 'nombre' => 'INGENIERÍA CATASTRAL'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 30, 'nombre' => 'INGENIERÍA CIVIL'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 31, 'nombre' => 'INGENIERÍA DE MINAS'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 32, 'nombre' => 'INGENIERÍA DE SISTEMAS'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 33, 'nombre' => 'INGENIERÍA DE TELECOMUNICACIONES'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 34, 'nombre' => 'INGENIERÍA ELÉCTRICA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 35, 'nombre' => 'INGENIERÍA ELECTRÓNICA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 36, 'nombre' => 'INGENIERÍA INDUSTRIAL'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 37, 'nombre' => 'INGENIERÍA MEDIOAMBIENTAL '),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 38, 'nombre' => 'INGENIERÍA QUÍMICA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 39, 'nombre' => 'INGENIERÍA TELEMÁTICA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 40, 'nombre' => 'INGENIERÍA TOPOGRAFICA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 41, 'nombre' => 'INGENIERO AERONAUTICO'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 42, 'nombre' => 'MATEMÁTICAS'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 43, 'nombre' => 'MEDICINA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 44, 'nombre' => 'MEDICINA VETERINARIA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 45, 'nombre' => 'MERCADEO'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 46, 'nombre' => 'ODONTOLOGÍA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 47, 'nombre' => 'PERIODISTA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 48, 'nombre' => 'PROFESIONAL DE LA EDUCACIÓN'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 49, 'nombre' => 'PROFESIONAL EN NEGOCIOS INTERNACIONALES'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 50, 'nombre' => 'PSICOLOGÍA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 51, 'nombre' => 'QUIMICA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 52, 'nombre' => 'RELACIONES INTERNACIONALES Y ESTUDIOS POLITICOS'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 53, 'nombre' => 'RELACIONES INTERNACIONALES Y FINANZAS'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 54, 'nombre' => 'SALUD PÚBLICA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 55, 'nombre' => 'SOCIOLOGIA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 56, 'nombre' => 'TERAPIA FISICA'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 57, 'nombre' => 'TERAPIA OCUPACIONAL'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 58, 'nombre' => 'TRABAJO SOCIAL'),
            array('tipo' => 'profesiones', 'tipo_valor' => 2, 'valor_numero' => 59, 'nombre' => 'ZOOTECNIA'),

            /* Etapas del cronograma */
            array('tipo' => 'etapas_crono', 'tipo_valor' => 2, 'valor_numero' => 1, 'nombre' => 'ETAPA DE PLANEACIÓN'),
            array('tipo' => 'etapas_crono', 'tipo_valor' => 2, 'valor_numero' => 2, 'nombre' => 'ETAPA DE EJECUCIÓN'),
            array('tipo' => 'etapas_crono', 'tipo_valor' => 2, 'valor_numero' => 3, 'nombre' => 'ETAPA DE INFORME'),
            array('tipo' => 'etapas_crono', 'tipo_valor' => 2, 'valor_numero' => 4, 'nombre' => 'ETAPA DE CIERRE'),

            /* Actuaciones posteriores */
            array('tipo' => 'actuaciones_posteriores', 'tipo_valor' => 2, 'valor_numero' => 1, 'nombre' => 'ACCIONES INTERNAS'),
            array('tipo' => 'actuaciones_posteriores', 'tipo_valor' => 2, 'valor_numero' => 2, 'nombre' => 'COMUNICACIÓN A LA ENTIDAD VIGILADA'),
            array('tipo' => 'actuaciones_posteriores', 'tipo_valor' => 2, 'valor_numero' => 3, 'nombre' => 'ENVÍO A OTRAS ENTIDADES'),
            array('tipo' => 'actuaciones_posteriores', 'tipo_valor' => 2, 'valor_numero' => 4, 'nombre' => 'RESPUESTA ENTIDAD VIGILADA'),
            array('tipo' => 'actuaciones_posteriores', 'tipo_valor' => 2, 'valor_numero' => 5, 'nombre' => 'RESPUESTA OBSERVACIONES ENTIDAD'),
            array('tipo' => 'actuaciones_posteriores', 'tipo_valor' => 2, 'valor_numero' => 6, 'nombre' => 'CIERRE DEFINITIVO DE LA ACCIÓN'),

            /* Actuaciones posteriores acciones_internas */
            array('tipo' => 'actuaciones_posteriores', 'tipo_valor' => 2, 'valor_numero' => 1, 'nombre' => 'PERSONERÍA DELEGADA PARA LA COORDINACIÓN DE GESTIÓN DE LAS PERSONERÍAS LOCALES'),
            array('tipo' => 'actuaciones_posteriores', 'tipo_valor' => 2, 'valor_numero' => 2, 'nombre' => 'PERSONERÍA DELEGADA PARA LA COORDINACIÓN DEL MINISTERIO PÚBLICO Y LOS DERECHOS HUMANOS'),
            array('tipo' => 'actuaciones_posteriores', 'tipo_valor' => 2, 'valor_numero' => 3, 'nombre' => 'SECRETARIA GENERAL'),
            array('tipo' => 'actuaciones_posteriores', 'tipo_valor' => 2, 'valor_numero' => 4, 'nombre' => 'PERSONERÍA AUXILIAR'),
            array('tipo' => 'actuaciones_posteriores', 'tipo_valor' => 2, 'valor_numero' => 5, 'nombre' => 'CONTROL INTERNO'),
            array('tipo' => 'actuaciones_posteriores', 'tipo_valor' => 2, 'valor_numero' => 6, 'nombre' => 'OFICINA ASESORA DE COMUNICACIONES'),
            array('tipo' => 'actuaciones_posteriores', 'tipo_valor' => 2, 'valor_numero' => 7, 'nombre' => 'DESPACHO'),
            array('tipo' => 'actuaciones_posteriores', 'tipo_valor' => 2, 'valor_numero' => 8, 'nombre' => 'PERSONERÍA DELEGADA PARA LA COORDINACIÓN DE POTESTAD DISCIPLINARIA'),
            array('tipo' => 'actuaciones_posteriores', 'tipo_valor' => 2, 'valor_numero' => 9, 'nombre' => 'OFICINA DE CONTROL INTERNO DISCIPLINARIO'),

            /* Actuaciones posteriores */
            array('tipo' => 'tipo_acta', 'tipo_valor' => 2, 'valor_numero' => 1, 'nombre' => 'ACTA TEMA PRINCIPAL'),
            array('tipo' => 'tipo_acta', 'tipo_valor' => 2, 'valor_numero' => 2, 'nombre' => 'ACTA APROBACIÓN PLAN DE TRABAJO'),
            array('tipo' => 'tipo_acta', 'tipo_valor' => 2, 'valor_numero' => 3, 'nombre' => 'ACTA VIABILIDAD DELEGADO PLAN DE GESTIÓN'),
            array('tipo' => 'tipo_acta', 'tipo_valor' => 2, 'valor_numero' => 4, 'nombre' => 'ACTA MESA DE TRABAJO CON ENLACE'),

            /* Estados */
            array('tipo' => 'estados_acciones', 'tipo_valor' => 2, 'valor_numero' => 1, 'valor_texto' => 'info', 'nombre' => 'CREADA'),
            array('tipo' => 'estados_acciones', 'tipo_valor' => 2, 'valor_numero' => 2, 'valor_texto' => 'info', 'nombre' => 'DECLARACION DELEGADO'),
            array('tipo' => 'estados_acciones', 'tipo_valor' => 2, 'valor_numero' => 3, 'valor_texto' => 'info', 'nombre' => 'EN PLAN DE TRABAJO'),
            array('tipo' => 'estados_acciones', 'tipo_valor' => 2, 'valor_numero' => 4, 'valor_texto' => 'danger', 'nombre' => 'RECHAZO PLAN DE TRABAJO'),
            array('tipo' => 'estados_acciones', 'tipo_valor' => 2, 'valor_numero' => 5, 'valor_texto' => 'success', 'nombre' => 'APROBADO PLAN DE TRABAJO'),
            array('tipo' => 'estados_acciones', 'tipo_valor' => 2, 'valor_numero' => 6, 'valor_texto' => 'info', 'nombre' => 'EQUIPO PLAN GESTION'),
            array('tipo' => 'estados_acciones', 'tipo_valor' => 2, 'valor_numero' => 7, 'valor_texto' => 'danger', 'nombre' => 'RECHAZO PLAN DE GESTION'),
            array('tipo' => 'estados_acciones', 'tipo_valor' => 2, 'valor_numero' => 8, 'valor_texto' => 'success', 'nombre' => 'APROBADO PLAN DE GESTION'),
            array('tipo' => 'estados_acciones', 'tipo_valor' => 2, 'valor_numero' => 9, 'valor_texto' => 'info', 'nombre' => 'INFORME ENTREGADO'),
            array('tipo' => 'estados_acciones', 'tipo_valor' => 2, 'valor_numero' => 10, 'valor_texto' => 'danger', 'nombre' => 'RECHAZO INFORME'),
            array('tipo' => 'estados_acciones', 'tipo_valor' => 2, 'valor_numero' => 11, 'valor_texto' => 'success', 'nombre' => 'APROBADO INFORME'),
            array('tipo' => 'estados_acciones', 'tipo_valor' => 2, 'valor_numero' => 12, 'valor_texto' => 'info', 'nombre' => 'ATUACION POSTERIOR'),
            array('tipo' => 'estados_acciones', 'tipo_valor' => 2, 'valor_numero' => 13, 'valor_texto' => 'success', 'nombre' => 'ACCION CERRADA'),

            array('tipo' => 'estados_plant', 'tipo_valor' => 2, 'valor_numero' => 1, 'valor_texto' => 'info', 'nombre' => 'CREADO'),
            array('tipo' => 'estados_plant', 'tipo_valor' => 2, 'valor_numero' => 2, 'valor_texto' => 'info', 'nombre' => 'FIRMADO DELEGADO(A)'),
            array('tipo' => 'estados_plant', 'tipo_valor' => 2, 'valor_numero' => 3, 'valor_texto' => 'danger', 'nombre' => 'RECHAZADO'),
            array('tipo' => 'estados_plant', 'tipo_valor' => 2, 'valor_numero' => 4, 'valor_texto' => 'info', 'nombre' => 'ACTUALIZADO'),
            array('tipo' => 'estados_plant', 'tipo_valor' => 2, 'valor_numero' => 5, 'valor_texto' => 'success', 'nombre' => 'APROBADO'),

            array('tipo' => 'estados_plang', 'tipo_valor' => 2, 'valor_numero' => 1, 'valor_texto' => 'info', 'nombre' => 'EQUIPO SELECCIONADO'),
            array('tipo' => 'estados_plang', 'tipo_valor' => 2, 'valor_numero' => 2, 'valor_texto' => 'info', 'nombre' => 'CREADO'),
            array('tipo' => 'estados_plang', 'tipo_valor' => 2, 'valor_numero' => 3, 'valor_texto' => 'info', 'nombre' => 'DECLARACIONES FIRMADAS'),
            array('tipo' => 'estados_plang', 'tipo_valor' => 2, 'valor_numero' => 4, 'valor_texto' => 'info', 'nombre' => 'VIABILIDAD DELEGADO'),
            array('tipo' => 'estados_plang', 'tipo_valor' => 2, 'valor_numero' => 5, 'valor_texto' => 'info', 'nombre' => 'MESA ENLACE'),
            array('tipo' => 'estados_plang', 'tipo_valor' => 2, 'valor_numero' => 6, 'valor_texto' => 'danger', 'nombre' => 'RECHAZADO'),
            array('tipo' => 'estados_plang', 'tipo_valor' => 2, 'valor_numero' => 7, 'valor_texto' => 'info', 'nombre' => 'ACTUALIZADO'),
            array('tipo' => 'estados_plang', 'tipo_valor' => 2, 'valor_numero' => 8, 'valor_texto' => 'success', 'nombre' => 'APROBADO'),
        ];
        for ($i=0; $i < count($datos); $i++) {
            ListasModel::create($datos[$i]);
        }
    }
}
